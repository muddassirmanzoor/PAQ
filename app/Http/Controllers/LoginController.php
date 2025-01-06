<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schools;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display a form of the login.
     */
    public function login(): View
    {
        return view('login');
    }

    /**
     * Check Login credentials.
     */
    public function checkLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            if(Auth::user()->hasRole('DEO')){
                return redirect()->intended('assembly-question'); // Change to your desired redirect route
            }elseif(Auth::user()->hasRole('Sectary')){
                return redirect()->intended('/dashboard'); // Change to your desired redirect route
            }else{
                return redirect()->intended('/assembly-question-list'); // Change to your desired redirect route
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Display a form of the login.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // Change to your desired redirect route
    }
}
