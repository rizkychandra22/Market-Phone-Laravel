<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function indexSeller()
    {
        return Inertia::render('Root/UserSellers');
    }

    // endpoint AJAX untuk DataTables
    public function dataSeller(Request $request)
    {
        $users = User::role('Seller')
            ->select('name', 'username', 'email', 'phone', 'address', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $users]);
    }

    public function indexCustomer()
    {
        return Inertia::render('Root/UserCustomers');
    }

    // endpoint AJAX untuk DataTables
    public function dataCustomer(Request $request)
    {
        $users = User::role('Customer')
            ->select('name', 'username', 'email', 'phone', 'address', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $users]);
    }
}
