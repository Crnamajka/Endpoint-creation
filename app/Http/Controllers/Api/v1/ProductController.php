<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Exception;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('productVariants')->get();
            return response()->json($products, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ProductName' => 'required|string|max:255',
                'ProductDescription' => 'required|string|max:200',
                'Price' => 'required|numeric',
            ]);

            $otherAttributes = [];
            if ($request->filled('brand')) {
                $otherAttributes['brand'] = $request->brand;
            }
            if ($request->filled('collection')) {
                $otherAttributes['collection'] = $request->collection;
            }
            if ($request->filled('genre')) {
                $otherAttributes['genre'] = $request->genre;
            }

            $product = Product::create([
                'ProductName' => $validated['ProductName'],
                'ProductDescription' => $validated['ProductDescription'],
                'Price' => $validated['Price'],
                'OtherAttributes' => $otherAttributes,
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with('productVariants')->find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            return response()->json($product, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $validated = $request->validate([
                'ProductName' => 'sometimes|string|max:255',
                'ProductDescription' => 'sometimes|string|max:200',
                'Price' => 'sometimes|numeric',
            ]);

            if (isset($validated['ProductName'])) {
                $product->ProductName = $validated['ProductName'];
            }
            if (isset($validated['ProductDescription'])) {
                $product->ProductDescription = $validated['ProductDescription'];
            }
            if (isset($validated['Price'])) {
                $product->Price = $validated['Price'];
            }

            $otherAttrs = $product->OtherAttributes ?? [];
            if ($request->filled('brand')) {
                $otherAttrs['brand'] = $request->brand;
            }
            if ($request->filled('collection')) {
                $otherAttrs['collection'] = $request->collection;
            }
            if ($request->filled('genre')) {
                $otherAttrs['genre'] = $request->genre;
            }
            $product->OtherAttributes = $otherAttrs;

            $product->save();

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Product::with('productVariants');

            if ($request->filled('name')) {
                $query->where('ProductName', 'LIKE', '%'.$request->name.'%');
            }
            if ($request->filled('color')) {
                $query->whereHas('productVariants', function ($q) use ($request) {
                    $q->where('Color', 'LIKE', '%'.$request->color.'%');
                });
            }
            if ($request->filled('size')) {
                $query->whereHas('productVariants', function ($q) use ($request) {
                    $q->where('Size', $request->size);
                });
            }
            if ($request->filled('price')) {
                $query->where('Price', '<=', $request->price);
            }

            $products = $query->get();

            if ($request->filled('brand')) {
                $products = $products->filter(function($p) use($request) {
                    return isset($p->OtherAttributes['brand'])
                        && $p->OtherAttributes['brand'] == $request->brand;
                });
            }
            if ($request->filled('collection')) {
                $products = $products->filter(function($p) use($request) {
                    return isset($p->OtherAttributes['collection'])
                        && $p->OtherAttributes['collection'] == $request->collection;
                });
            }
            if ($request->filled('genre')) {
                $products = $products->filter(function($p) use($request) {
                    return isset($p->OtherAttributes['genre'])
                        && $p->OtherAttributes['genre'] == $request->genre;
                });
            }

            return response()->json($products->values(), 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error searching products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
