<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LessorLoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login-lessor');
    }

    /**
     * Handle the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lessor = Lessor::where('email', $request->email)->first();

        if ($lessor && Hash::check($request->password, $lessor->password)) {
            // Optionally: Set up a session or token here if needed
            Auth::loginUsingId($lessor->id); // Log in using the lessor's ID

            return redirect()->route('tenant.home')->with('status', 'Login successful!');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    /**
     * Log the lessor out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('lessor.login')->with('status', 'Logged out successfully.');
    }
}
