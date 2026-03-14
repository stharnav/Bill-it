<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')->latest()->get();
        return view('about.logs', compact('logs'), ['currentPage' => 'about-logs']);
    }
}
