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
    // Crear una orden basada en los productos en el carrito del usuario autenticado
    public function createOrder(Request $request)
    {
        try {
            $user = Auth::user();
        
            // Obtener el carrito abierto del usuario
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
        
            // Crear la orden
            $order = Order::create([
                'UserID' => $user->UserID,
                'OrderDate' => now(),
                'TotalAmount' => $cartItems->sum('UnitPrice'),  // Asegúrate de que 'UnitPrice' esté correctamente definido
                'OrderStatus' => 'Pending',
                'PaymentMethod' => $request->payment_method,
                'ShippingAddress' => $request->shipping_address,
            ]);
        
            // Crear los ítems de la orden
            foreach ($cartItems as $cartItem) {
                $productVariant = $cartItem->productVariant;  // Relación cargada con 'cartItems.productVariant'
        
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
    

    // Listar todas las órdenes del usuario autenticado
    public function listOrders()
    {
        try {
            $user = Auth::user();
        
            $orders = Order::with('orderItems.productVariant.product')  // Cargar el producto relacionado a través de la variante
                            ->where('UserID', $user->UserID)
                            ->get();
        
            return response()->json([
                'orders' => $orders->map(function ($order) {
                    return [
                        'order' => $order,  // Detalles de la orden
                        'orderItems' => $order->orderItems->map(function ($item) {
                            return [
                                'variant' => $item->productVariant,  // Detalles de la variante del producto
                                'product' => $item->productVariant->product,  // Detalles del producto
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
    

    // Obtener los detalles de una orden específica
    public function orderDetails($id)
    {
        try {
            $user = Auth::user();
        
            // Cargar los ítems de la orden y los productos asociados
            $order = Order::with('orderItems.productVariant.product')
                            ->where('UserID', $user->UserID)
                            ->where('OrderID', $id)
                            ->first();
        
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
        
            return response()->json([
                'order' => $order,  // Detalles de la orden
                'orderItems' => $order->orderItems->map(function ($item) {
                    return [
                        'variant' => $item->productVariant,  // Detalles de la variante
                        'product' => $item->productVariant->product,  // Detalles del producto
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
