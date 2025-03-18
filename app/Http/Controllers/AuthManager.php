<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    function login()
    {
        return view("auth.login");
    }
    function loginPost(Request $request)
    {
        $request->validate([
            // "email" => "required|email|unique:users",
            // "password" => "required|min:8",
            "email" => "required",
            "password" => "required",
        ]);
        $credentials = $request->only("email", "password");
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route("index"));
        }
        return redirect(to: "login")->with("error", "Invalid email or password");
    }
    function register()
    {
        return view("auth.register");
    }
    function registerPost(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required",
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            return redirect()->intended(route("login"))->with("success", "You have been registered successfully");
        }
        return redirect(route("register"))->with("error", "Something went wrong");
    }
    function logout()
    {
        Auth::logout();
        Session::flush();
        //This is a method from Laravel's Auth facade that logs out the currently authenticated user.
        // When called, it will: Clear the user's session data. Invalidate the session token 
        //(if you're using token-based authentication).
        // Effectively log out the user, so they are no longer authenticated.
        return redirect("login");
    }
}