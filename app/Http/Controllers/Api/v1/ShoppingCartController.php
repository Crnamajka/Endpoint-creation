<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Exception;

class ShoppingCartController extends Controller
{
    // Verificar el carrito de compras del usuario autenticado
    public function index(Request $request)
    {
        try {
            $cart = ShoppingCart::where('UserID', $request->user()->UserID)
                                ->where('Status', 'open')
                                ->with(['cartItems.productVariant'])
                                ->first();

            if (!$cart) {
                return response()->json([
                    'message' => 'The user does not have an open cart',
                ], 200);
            }

            $items = $cart->cartItems->map(function ($item) {
                return [
                    'CartItemID' => $item->CartItemID,
                    'VariantID' => $item->VariantID,
                    'Color' => $item->productVariant->Color ?? null,
                    'Size' => $item->productVariant->Size ?? null,
                    'Quantity' => $item->Quantity,
                    'UnitPrice' => $item->UnitPrice,
                ];
            });

            return response()->json([
                'CartID' => $cart->CartID,
                'Status' => $cart->Status,
                'CreatedDate' => $cart->CreatedDate,
                'items' => $items
            ], 200);

        } catch (Exception $e) {
            // Manejo de excepciones generales
            return response()->json([
                'message' => 'Error retrieving shopping cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Agregar un artículo al carrito de compras
    public function addItem(Request $request)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'VariantID' => 'required|integer',
                'Quantity' => 'required|integer|min:1'
            ]);

            // Crear el carrito si no existe
            $cart = ShoppingCart::firstOrCreate(
                [
                    'UserID' => $request->user()->UserID,
                    'Status' => 'open'
                ],
                ['CreatedDate' => now()]
            );

            // Buscar la variante del producto
            $variant = ProductVariant::find($request->VariantID);
            if (!$variant) {
                return response()->json([
                    'message' => 'The Product Variant does not exist'
                ], 404);
            }

            // Buscar si el artículo ya está en el carrito
            $cartItem = CartItem::where('CartID', $cart->CartID)
                                ->where('VariantID', $request->VariantID)
                                ->first();

            if ($cartItem) {
                // Si el artículo ya existe, se actualiza la cantidad
                $cartItem->Quantity += $request->Quantity;
            } else {
                // Si el artículo no existe, se crea un nuevo CartItem
                $cartItem = new CartItem();
                $cartItem->CartID = $cart->CartID;
                $cartItem->VariantID = $variant->VariantID;
                $cartItem->Quantity = $request->Quantity;
                $cartItem->UnitPrice = 700; // Aquí podrías ajustar el precio según lo que corresponda
            }

            $cartItem->save();

            return response()->json([
                'message' => 'The product was added to the cart successfully',
                'cartItem' => $cartItem
            ], 201);

        } catch (Exception $e) {
            // Manejo de excepciones generales
            return response()->json([
                'message' => 'Error adding product to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizar la cantidad de un artículo en el carrito
    public function updateItem(Request $request, $cartItemId)
    {
        try {
            // Validación de la cantidad
            $request->validate([
                'Quantity' => 'required|integer|min:1'
            ]);

            // Buscar el CartItem en la base de datos
            $cartItem = CartItem::join('shopping_carts', 'cart_items.CartID', '=', 'shopping_carts.CartID')
                                ->where('shopping_carts.UserID', $request->user()->UserID)
                                ->where('cart_items.CartItemID', $cartItemId)
                                ->select('cart_items.*')
                                ->first();

            if (!$cartItem) {
                return response()->json(['message' => 'Cart item not found or does not belong to the user'], 404);
            }

            // Actualizar la cantidad
            $cartItem->Quantity = $request->Quantity;
            $cartItem->save();

            return response()->json([
                'message' => 'Quantity updated successfully',
                'cartItem' => $cartItem
            ], 200);

        } catch (Exception $e) {
            // Manejo de excepciones generales
            return response()->json([
                'message' => 'Error updating cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un artículo del carrito de compras
    public function removeItem(Request $request, $cartItemId)
    {
        try {
            // Buscar el CartItem en la base de datos
            $cartItem = CartItem::join('shopping_carts', 'cart_items.CartID', '=', 'shopping_carts.CartID')
                                ->where('shopping_carts.UserID', $request->user()->UserID)
                                ->where('cart_items.CartItemID', $cartItemId)
                                ->select('cart_items.*')
                                ->first();

            if (!$cartItem) {
                return response()->json(['message' => 'The item was not found or does not belong to the user'], 404);
            }

            // Eliminar el artículo del carrito
            $cartItem->delete();

            return response()->json([
                'message' => 'Item deleted from cart'
            ], 200);

        } catch (Exception $e) {
            // Manejo de excepciones generales
            return response()->json([
                'message' => 'Error removing item from cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
