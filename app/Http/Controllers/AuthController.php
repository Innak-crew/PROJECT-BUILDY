<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
 
class AuthController extends Controller
{
    public function login (){
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('message', 'You have been logged out successfully.');
    }

    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Attempt authentication
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $role = Auth::user()->role;
            if ($role  == 'admin') {
                return redirect()->intended(route('admin.index'));
            } else if ($role == 'admin') {
                return redirect()->intended(route('manager.index'));
            }else if ($role == 'developer') {
                return redirect()->intended(route('dev.logs'));
            }
        }

        // Authentication failed
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // Incorrect password
            if (!Hash::check($request->password, $user->password)) {
                Log::create([
                    'message' => 'Failed login attempt with email: ' . $request->email . ' due to incorrect password.',
                    'level' => 'warning',
                    'type' => 'login',
                    'ip_address' => $request->ip(),
                    'context' => 'web',
                    'source' => 'login_form',
                    'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
                ]);
                return back()->withErrors([
                    'password' => 'The provided password is incorrect.',
                ])->withInput();
            }
        } else {
            // Email not found
            Log::create([
                'message' => 'Failed login attempt with non-existent email: ' . $request->email,
                'level' => 'warning',
                'type' => 'login',
                'ip_address' => $request->ip(),
                'context' => 'web',
                'source' => 'login_form',
                'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
            ]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        Log::create([
            'message' => 'Failed login attempt with email: ' . $request->email,
            'level' => 'warning',
            'type' => 'login',
            'ip_address' => $request->ip(),
            'context' => 'web',
            'source' => 'login_form',
            'extra_info' => json_encode(['user_agent' => $request->header('User-Agent')])
        ]);
    
        // Authentication failed due to incorrect credentials
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }       
}