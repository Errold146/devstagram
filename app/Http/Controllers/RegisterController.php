<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Normalizar el username antes de validar para que la regla
        // 'unique' compare el mismo valor que se guardará en la BD
        $request->merge([
            'username' => Str::slug($request->username, '_'),
        ]);

        // Validación
        $validated = $request->validate([
            'name' => 'required|max:30|min:3',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Redireccionar
        return redirect()->route('post.index', $user->username);
    }
}
