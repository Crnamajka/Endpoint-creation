<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShoppingCart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    
    public function createOrder(Request $request)
    {
        try {
            $user = Auth::user();
        
            
            $shoppingCart = ShoppingCart::where('UserID', $user->UserID)
                                        ->where('Status', 'open')
                                        ->with('cartItems.productVariant') 
                                        ->first();
        
            if (!$shoppingCart) {
                return response()->json(['message' => 'No active cart found for the user'], 404);
            }
        
            $cartItems = $shoppingCart->cartItems;
        
            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }
        
            
            $order = Order::create([
                'UserID' => $user->UserID,
                'OrderDate' => now(),
                'TotalAmount' => $cartItems->sum('UnitPrice'),  
                'OrderStatus' => 'Pending',
                'PaymentMethod' => $request->payment_method,
                'ShippingAddress' => $request->shipping_address,
            ]);
        
          
            foreach ($cartItems as $cartItem) {
                $productVariant = $cartItem->productVariant; 
        
                if ($productVariant) {
                    OrderItem::create([
                        'OrderID' => $order->OrderID,
                        'VariantID' => $productVariant->VariantID,
                        'Quantity' => $cartItem->Quantity,
                        'UnitPrice' => $cartItem->UnitPrice,
                    ]);
                }
            }
        
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function listOrders()
    {
        try {
            $user = Auth::user();
        
            $orders = Order::with('orderItems.productVariant.product') 
                            ->where('UserID', $user->UserID)
                            ->get();
        
            return response()->json([
                'orders' => $orders->map(function ($order) {
                    return [
                        'order' => $order, 
                        'orderItems' => $order->orderItems->map(function ($item) {
                            return [
                                'variant' => $item->productVariant, 
                                'product' => $item->productVariant->product,
                            ];
                        })
                    ];
                })
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    
    public function orderDetails($id)
    {
        try {
            $user = Auth::user();
        
        
            $order = Order::with('orderItems.productVariant.product')
                            ->where('UserID', $user->UserID)
                            ->where('OrderID', $id)
                            ->first();
        
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
        
            return response()->json([
                'order' => $order,
                'orderItems' => $order->orderItems->map(function ($item) {
                    return [
                        'variant' => $item->productVariant,
                        'product' => $item->productVariant->product,
                    ];
                })
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
