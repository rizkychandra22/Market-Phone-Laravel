<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\brand;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Seller/Product/Index');
    }

    public function create()
    {
        $brands = brand::all();
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

        $brand = brand::create($validated);

        $logoUrl = $brand->logo_brand ? Storage::url('brands/' . $brand->logo_brand) : null;

        return response()->json([
            'success' => 'Brand created successfully',
            'brand' => $brand,
            'logo_url' => $logoUrl,
        ], Response::HTTP_CREATED);
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'chipset' => 'required|string|max:255',
            'software' => 'required|string|max:255',
            'display' => 'required|string|max:255',
            'dimensi' => 'required|string|max:255',
            'camera' => 'required|string|max:255',
            'baterai' => 'required|string|max:255',
            'network' => 'required|string|max:255',
            'konektivitas' => 'required|string|max:255',
        ]);
        
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        product::create($data);

        return redirect()->route('seller.products.create')
                        ->with('success', 'Product created successfully');
    }
}

// if ($request->hasFile('image_product')) {
            
//             $file = $request->file('image_product');
//             $filename = preg_replace('/\s+/', '_', strtolower($request->name)) 
//                         . '_' 
//                         . now()->format('Ymd_His') 
//                         . '.' 
//                         . $file->getClientOriginalExtension();

//             // simpan ke storage
//             $file->storeAs('products', $filename, 'public');
//         }
