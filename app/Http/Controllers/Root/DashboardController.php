<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{ 
    public function index()
    {
        // dd("Halo Super Admin");
        return Inertia::render('RootDashboard'); 
    }
}
