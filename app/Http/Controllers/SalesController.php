<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        return view('sales.sale', compact('sales'), ['currentPage' => 'sales']);
    }
}
