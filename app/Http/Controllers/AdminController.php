<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json(['message' => 'Invalid file upload'], 400);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/uploads', $filename);
        $url = Storage::url('uploads/' . $filename);

        return response()->json(['url' => $url], 200);
    }

    public function uploadDashboard(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|string|max:255',
            ]);

            $dashboard = Dashboard::create([
                'category' => 'dashboard',
                'image' => trim($request->image),
            ]);

            return response()->json([
                'message' => 'Dashboard saved successfully',
                'dashboard' => $dashboard
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function fetchDashboard(Request $request)
    {
        try {
            $dashboard = Dashboard::all();

            return response()->json([
                'message' => 'Dashboards fetched successfully',
                'dashboard' => $dashboard
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteDashboard($id)
    {
        try {
            $dashboard = Dashboard::find($id);

            if (!$dashboard) {
                return response()->json(['message' => 'Dashboard not found'], 404);
            }

            if ($dashboard->image) {
                $storagePath = str_replace('/storage/', 'public/', $dashboard->image);
                if (Storage::exists($storagePath)) {
                    Storage::delete($storagePath);
                }
            }

            $dashboard->delete();

            return response()->json(['message' => 'Dashboard deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function uploadProduct(Request $request)
    {
        try {
            $product = Product::create([
                'category'     => $request->category,
                'subCategory' => $request->subCategory,
                'brand'        => $request->brand,
                'title'        => $request->title,
                'price'        => $request->price,
                'quantity'     => $request->quantity,
                'description'  => $request->description,
                'image'        => $request->image,
            ]);

            return response()->json([
                'message' => 'Product saved successfully',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchProducts()
    {
        try {
            $products = Product::all();

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


    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->update([
                'title'        => $request->title,
                'price'        => $request->price,
                'quantity'     => $request->quantity,
                'description'  => $request->description,
                'category'     => $request->category,
                'subCategory'  => $request->subCategory,
                'brand'        => $request->brand,
            ]);

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
 
            $relativePath = str_replace('/storage/', '', $product->image);
 
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
