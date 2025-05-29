<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function fetchProducts(Request $request)
    {
        try {
            $category = strtolower($request->query('category'));
            $subCategory = $request->query('subCategory');
            $brand = $request->query('brand');

            $query = Product::query();

            if ($category === 'all') {
                $query->whereIn('category', ['men', 'women', 'kids']);
            } elseif ($category) {
                $query->where('category', strtolower($category));
            }

            if ($subCategory) {
                $subCategories = is_array($subCategory) ? $subCategory : [$subCategory];
                $query->whereIn('subCategory', array_map('strtolower', $subCategories));
            }

            if ($brand) {
                $brands = is_array($brand) ? $brand : [$brand];
                $query->whereIn('brand', array_map('strtolower', $brands));
            }

            $products = $query->get();

            return response()->json([
                'message' => 'Products fetched successfully',
                'product' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchAddress($id)
    {
        try {
            $addresses = Address::where('user_id', $id)->get();

            return response()->json([
                'message' => 'Address fetched successfully',
                'address' => $addresses
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function uploadAddress(Request $request)
    {
        try {
            $request->validate([
                'userId' => 'required|exists:users,id',
                'address' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|string',
                'phone' => 'required|string',
            ]);

            $existing = Address::where('user_id', $request->userId)->count();
            if ($existing >= 3) {
                return response()->json(['message' => 'Only 3 addresses per user are allowed.'], 500);
            }

            $address = Address::create([
                'user_id' => $request->userId ,
                'address' => $request->address ,
                'state' => $request->state ,
                'pincode' => $request->pincode ,
                'phone' => $request->phone ,
                'note' => $request->note
            ]); 

            return response()->json(['message' => 'Address saved successfully', 'address' => $address], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        try {
            $address = Address::find($id);

            if (!$address) {
                return response()->json(['message' => 'Address not found'], 404);
            }

            $address->update($request->only(['address', 'state', 'pincode', 'phone', 'note']));

            return response()->json(['message' => 'Address updated successfully', 'address' => $address], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteAddress($id)
    {
        try {
            $address = Address::find($id);

            if (!$address) {
                return response()->json(['message' => 'Address not found'], 404);
            }

            $address->delete();

            return response()->json(['message' => 'Address deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function fetchOrder($id)
    {
        try {
            if($id === 'all'){
                $orders = Order::with('address')
                ->get(); 
            }else{
                $orders = Order::where('user_id', $id)
                ->with('address')
                ->get(); 
            }

            return response()->json([
                'message' => 'Orders fetched successfully',
                'order' => $orders
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function fetchOrderDetail($id)
    {
        try { 
            $orderDetail = OrderDetail::where('order_id', $id)
            ->with('product')
            ->get();  

            return response()->json([
                'message' => 'Orders fetched successfully',
                'orderDetail' => $orderDetail
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'addressId' => 'required|exists:addresses,id',
            'totalAmount' => 'required|numeric',
            'totalItems' => 'required|integer',
            'orderDetails' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->userId,
                'address_id' => $request->addressId,
                'total_amount' => $request->totalAmount,
                'total_items' => $request->totalItems,
                'status' => 'pending'
            ]);

            foreach ($request->orderDetails as $detail) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $detail['productId'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Order placed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateOrderStatus($id,$value)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->update(['status' => $value]);

            return response()->json(['message' => 'Order updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
