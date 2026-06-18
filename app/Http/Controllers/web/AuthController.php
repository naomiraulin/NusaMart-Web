<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Notification;
use App\Services\UserService;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private IdGeneratorService $idGenerator
    ) {}

    // Menampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register Web
    public function register(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|unique:users,username',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string',
            'password'      => 'required|string|min:8',
            'role'          => 'required|in:BUYER,SELLER',
            // Wajib jika role SELLER
            'nik'           => 'required_if:role,SELLER|string|size:16',
            'bankName'      => 'required_if:role,SELLER|string',
            'accountNumber' => 'required_if:role,SELLER|string',
        ]);

        // Buat user baru
        $user = User::create([
            'idUser'   => $this->userService->generateUserId($request->role),
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);

        // Jika SELLER, simpan data seller
        if ($request->role === 'SELLER') {
            Seller::create([
                'idSeller'      => $user->idUser,
                'nik'           => $request->nik,
                'bankName'      => $request->bankName,
                'accountNumber' => $request->accountNumber,
                'ktpPhoto'      => null,
                'createAt'      => now(),
                'updateAt'      => now(),
            ]);
        }

        // Buat notifikasi welcome
        Notification::create([
            'idNotif'       => $this->idGenerator->generate('NTF', Notification::class, 'idNotif'),
            'idUser'        => $user->idUser,
            'title'         => 'Selamat Datang di NusaMart!',
            'body'          => $request->role === 'SELLER'
                                ? 'Akun seller kamu sudah aktif. Yuk mulai berjualan!'
                                : 'Selamat datang ' . $user->username . '! Yuk mulai belanja.',
            'type'          => 'SISTEM',
            'isRead'        => false,
            'referenceId'   => null,
            'referenceType' => 'SYSTEM',
            'createAt'      => now(),
        ]);

        // Langsung loginkan user setelah register via session web
        Auth::login($user);

        // Redirect ke homepage
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di NusaMart.');
    }

    // Menampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login Web
    public function login(Request $request)
    {
        $request->validate([
            'emailOrUsername' => 'required|string',
            'password'        => 'required|string',
        ]);

        // Cek apakah input berupa email atau username
        $loginType = filter_var($request->emailOrUsername, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Siapkan kredensial untuk dicocokkan
        $credentials = [
            $loginType => $request->emailOrUsername,
            'password' => $request->password
        ];

        // Lakukan pengecekan session auth
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'SELLER') {
                return redirect()->route('seller.dashboard');
            }

            if ($role === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        // Jika gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'emailOrUsername' => 'Username/email atau password salah.',
        ])->onlyInput('emailOrUsername');
    }

    // Proses Logout Web
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout.');
    }
}