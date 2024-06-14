<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dimensions' => 'nullable|string',
            'type' => 'required|in:Interior,Exterior,Both',
            'unit_id' => 'required|numeric',
            'rate_per' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('products.create')->withErrors($validator)->withInput();
        }
    
        $user_id = Auth::id();
    
        try {
            if ($request->hasFile('image')) {
                $timestamp = now()->format('YmdHis');
                $originalFileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFileName);
                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = $timestamp . '_' . $sanitizedFileName . '.' . $extension;
                $path = $request->file('image')->storeAs('products', $imageName, 'public');
                $imagepath = "/storage/" . $path;
            } else {
                $imagepath = null;
            }
    
            Products::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'description' => $request->description,
                'image_url' => $imagepath,
                'dimensions' => $request->dimensions,
                'unit_id' => $request->unit_id,
                'rate_per' => $request->rate_per,
            ]);
    
            return back()->with('message', 'Product Created successfully.');
    
        } catch (Exception $e) {
            return back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, string $encodedId) {

        $decodedId = base64_decode($encodedId);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dimensions' => 'nullable|string',
            'type' => 'required|in:Interior,Exterior,Both',
            'unit_id' => 'required|numeric',
            'rate_per' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        try {
            $imagepath = null;
    
            if ($request->hasFile('image')) {
                $timestamp = now()->format('YmdHis');
                $originalFileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFileName);
                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = $timestamp . '_' . $sanitizedFileName . '.' . $extension;
                $path = $request->file('image')->storeAs('products', $imageName, 'public');
                $imagepath = "/storage/" . $path;
            }
    
            $products = Products::find($decodedId);
            $products->name = $request->name;
            $products->description = $request->description;
            $products->dimensions = $request->dimensions;
            $products->unit_id = $request->unit_id;
            $products->rate_per = $request->rate_per;
            if($imagepath != null){
                $products->image_url = $imagepath; 
            }
            $products->save();
    
            return back()->with('message', 'Product updated successfully.');
    
        } catch (Exception $e) {
            return back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function destroy(string $encodedId) 
    {
        $decodedId = base64_decode($encodedId); 
        try {
            $products = Products::findOrFail($decodedId);
            $products->delete();
            return  back()->with('message', 'Product deteled successfully.');
        } catch (ModelNotFoundException $e) {
            return abort(404, 'Product not found'); 
        }
    }
}
