<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\PasswordResetMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $code = rand(100000, 999999);
        $email = $request->email;

        Cache::put('password_reset_' . $email, $code, now()->addMinutes(10));

        Mail::to($email)->send(new PasswordResetMail($code));

        return redirect()->route('password.reset', ['email' => $email])
                         ->with('status', 'We have emailed your password reset code!');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset', ['email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric',
            'password' => 'required|min:8|confirmed'
        ]);

        $cachedCode = Cache::get('password_reset_' . $request->email);

        if (!$cachedCode || $cachedCode != $request->code) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->hashed_password = \Hash::make($request->password);
        $user->save();

        // Remove the code from the cache
        Cache::forget('password_reset_' . $request->email);

        return redirect()->route('login')->with('status', 'Password has been reset successfully!');
    }
}