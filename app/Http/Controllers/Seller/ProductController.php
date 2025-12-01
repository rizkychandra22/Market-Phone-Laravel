<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
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
        return Inertia::render('Seller/Product/Create');
    }
}
