<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SysConfig\FiscalYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
        $fiscalYears = FiscalYear::whereIsActive(1)->get();
        return view('auth.login', compact('fiscalYears'));
    }

    public function loginAuth(Request $request): RedirectResponse
    {
        $request->validate([
           'email' => 'required|email',
           'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $rememberMe = $request->has('remember_me');

        if (Auth::attempt($credentials, $rememberMe))
        {
            $request->session()->regenerate();
            session(['FiscalYear' => $request->input('fiscal_year')]);

//            Config::set('global.FiscalYear', $request->input('fiscal_year'));
//            dd(Config::get('global.FiscalYear'));

            return redirect()->intended('accounts/dashboard')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withErrors('Oppes! You have entered invalid credentials');
    }

    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
