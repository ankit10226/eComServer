<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
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

            $dashboard->delete();

            return response()->json(['message' => 'Dashboard deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
