<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     * Check if admin user is within their shift hours.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Only check shift for admin users (kasir)
        if ($user->isAdmin() && $user->workShift) {
            $currentTime = now();
            
            if (!$user->workShift->isWithinShiftTime($currentTime)) {
                // Logout the user
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Get shift info for error message
                $shiftName = $user->workShift->nama_shift;
                $jamMulai = \Carbon\Carbon::parse($user->workShift->jam_mulai)->format('H:i');
                $jamSelesai = \Carbon\Carbon::parse($user->workShift->jam_selesai)->format('H:i');
                
                return redirect()->route('login')
                    ->withErrors([
                        'email' => "Anda hanya dapat login pada jam shift Anda ({$shiftName}: {$jamMulai} - {$jamSelesai}). Waktu sekarang: " . $currentTime->format('H:i'),
                    ]);
            }
        }
        
        return redirect()->intended($this->redirectPath());
    }
}
