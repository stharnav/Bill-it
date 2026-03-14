<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;

class UserController extends Controller
{
    public function welcome(){
        $saleCount = \App\Models\Sales::where('is_refund', 0)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $productCount = \App\Models\Product::count();
        $categoryCount = \App\Models\Category::count();
        $refundCount = \App\Models\Sales::where('is_refund', 1)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        return view('welcome', compact('saleCount', 'productCount', 'categoryCount', 'refundCount'), ['currentPage' => 'home']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            Log::create([
                'user_id' => auth()->id(),
                'description' => 'logged in',
            ]);

            return redirect('/');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255',
            'user_type' => 'required|integer',
        ]);

        $password = $request->input('username') . '123';
        try{
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->username = $request->input('username');
            $user->password = Hash::make($password);
            $user->user_type = $request->input('user_type');
            $user->save();

                Log::create([
                    'user_id' => auth()->id(),
                    'description' => 'created user: ' . $user->name,
                ]);
            return redirect()->back()->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user');
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');

        if ($request->hasFile('user_logo')) {
            if ($user->user_logo && file_exists(public_path('uploads/user/' . $user->user_logo))) {
                unlink(public_path('uploads/user/' . $user->user_logo));
            }

            $logo = $request->file('user_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/user'), $logoName);

            $user->user_logo = $logoName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $currentPassword = trim($request->input('old_password'));
        $newPassword = trim($request->input('new_password'));

        if (Hash::check($currentPassword, $user->password)) {
            $user->password = Hash::make($newPassword);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
    }

    public function index()
    {
        $users = User::all();
        return view('about.about-users', compact('users'), ['currentPage' => 'about-users']);
    }
}
