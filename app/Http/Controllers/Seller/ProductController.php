<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\brand;
use App\Models\product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Seller/Product/Index');
    }

    public function create()
    {
        $brands = brand::limit(4)->get();
        return Inertia::render('Seller/Product/Create', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo_brand' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo_brand')) {
            
            $file = $request->file('logo_brand');
            $filename = preg_replace('/\s+/', '_', strtolower($request->name)) 
                        . '_' 
                        . now()->format('Ymd_His') 
                        . '.' 
                        . $file->getClientOriginalExtension();

            // simpan ke storage
            $file->storeAs('brands', $filename, 'public');

            $validated['logo_brand'] = $filename;
        }

        brand::create($validated);

        return redirect()->route('seller.products.create')
                        ->with('success', 'Brand created successfully');
    }

     public function productStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'image_product' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'processor' => 'required|string|max:50',
            'memori' => 'required|string|max:50',
            'display' => 'required|string|max:50',
            'camera' => 'required|string|max:50',
            'baterai' => 'required|string|max:50',
            'software' => 'required|string|max:50',
            'konektivitas' => 'required|string|max:50',
            'stock' => 'required|integer',
        ]);
        if ($request->hasFile('image_product')) {
            
            $file = $request->file('image_product');
            $filename = preg_replace('/\s+/', '_', strtolower($request->name)) 
                        . '_' 
                        . now()->format('Ymd_His') 
                        . '.' 
                        . $file->getClientOriginalExtension();

            // simpan ke storage
            $file->storeAs('products', $filename, 'public');
        }

        product::create([
            'user_id' => $request->user_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image_product' => $filename,
            'stock' => $request->stock,
            'price' => $request->price,
            'processor' => $request->processor,
            'memori' => $request->memori,
            'display' => $request->display,
            'camera' => $request->camera,
            'baterai' => $request->baterai,
            'software' => $request->software,
            'konektivitas' => $request->konektivitas,
        ]);
        return redirect()->route('seller.products.create')
                        ->with('success', 'Product created successfully');
    }
}
