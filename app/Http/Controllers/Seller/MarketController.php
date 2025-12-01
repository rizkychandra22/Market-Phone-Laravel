<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketController extends Controller
{
    public function index()
    {
        return Inertia::render('Seller/Market/Index');
    }

    public function create()
    {
        return Inertia::render('Seller/Market/Create');
    }   
}
