<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validación
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Comprobar las credenciales
        if (! Auth::attempt($validated, $request->boolean('remember'))) {
            return back()->with('mensaje', 'Email y/o Password Inválidos.');
        }

        $request->session()->regenerate();

        return redirect()->route('post.index', Auth::user()->username);
    }
}
