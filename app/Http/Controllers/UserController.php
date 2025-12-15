<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }

    public function registerFunction(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);

        Auth::login($user);

        return redirect()->route('index');
    }

    public function loginFunction(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return back()->withErrors([
            'email/password' => 'Email salah',
        ])->onlyInput('email');
    }

    public function roleChecker(Request $request)
    {
        if (!Auth::check())
        {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role === 'admin')
        {
            return redirect()->route('admin.index');
        }
        elseif ($user->role === 'user')
        {
            return redirect()->route('user.index');
        }

        abort(403, 'Role tidak dikenali');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function inventory()
    {
        $items = Inventory::where('user_id', Auth::id())->with('product')->get();
        return view('user.inventory.index', compact('items'));
    }
}