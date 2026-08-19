<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah pengguna sedang login
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Cek apakah status akun masih aktif
            if (! $user->is_active) {
                Auth::logout(); // Logout pengguna

                return redirect('/login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
            }

            // 2. Cek apakah sudah melewati batas waktu aktivitas (7 hari)
            $lastActivity = Cookie::get('last_activity_time');
            $now = Carbon::now();

            // Jika tidak ada aktivitas selama 6 jam (360 menit), logout paksa
            if ($lastActivity && $now->diffInMinutes(Carbon::parse($lastActivity)) >= 360) {
                Auth::logout(); // Logout pengguna

                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }

            // Simpan waktu aktivitas terakhir di cookie (refresh setiap request)
            Cookie::queue('last_activity_time', $now->toDateTimeString(), 360); // 360 menit = 6 jam
        }

        return $next($request);
    }
}
