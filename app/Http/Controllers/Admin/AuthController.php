<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Auth,
    Log,
    RateLimiter,
};
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    const ROUTE_LIST = 'admin.product.list';
    const ROUTE_LOGIN = 'admin.login.view';

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::info('Admin login attempt', ['email' => $request->email]);

            throw ValidationException::withMessages([
                'email' => [
                    __(
                        'Too many login attempts. Please try again in :seconds seconds.',
                        ['seconds' => $seconds]
                    )
                ]
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($throttleKey);
            Log::info(
                'Admin logged in successfully',
                ['admin_id' => Auth::id(), 'email' => Auth::user()->email]
            );
            return redirect()->route(self::ROUTE_LIST);
        }

        RateLimiter::hit($throttleKey);

        Log::warning(
            'Admin login failed: Invalid credentials',
            ['email' => $request->email]
        );

        return back()->withErrors(['email' => 'Invalid login credentials'])
            ->withInput($request->only('email'));
    }

    public function logout()
    {
        Log::info('Admin logged out', ['admin_id' => Auth::id()]);

        Auth::logout();

        return redirect()->route(self::ROUTE_LOGIN);
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
    }
}
