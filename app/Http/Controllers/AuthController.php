<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'alamat' => 'required|max:255',
            'no_hp' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if (User::where('email', $validatedData['email'])->exists()) {
            toastr()->error('Email sudah terdaftar');
            return redirect()->back()->withInput();
        }

        User::create([
            'nama' => $validatedData['nama'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => 'pasien',
        ]);

        toastr()->success('Registrasi berhasil, silahkan login');
        return redirect('/login')->withInput();
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            toastr()->success('Selamat datang ' . $user->nama);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'dokter') {
                return redirect()->route('dokter.dashboard');
            } elseif ($user->role === 'pasien') {
                return redirect()->route('pasien.dashboard');
            } else {
                return abort(403, 'Unauthorized action.');
            }
        }

        toastr()->error('Email atau password salah');
        return redirect()->back()->withInput();
    }
    public function logout(Request $request)
    {
        // Clear remember me cookie jika ada
        if ($cookie = $request->cookie('remember_web_')) {
            \Cookie::queue(\Cookie::forget('remember_web_'));
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Tambahkan header keamanan
        $response = redirect('/login');
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        toastr()->success('Anda berhasil logout');
        return $response;
    }
}
