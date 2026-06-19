<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Models\UserAddress; 
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    /**
     * Halaman profil user.
     */
    public function show(): View
    {
        $user = Auth::user();
        
        // Mengambil daftar alamat milik user ini berdasarkan idUser 
        $addresses = UserAddress::where('idUser', Auth::id())->get();

        // Lempar data user dan addresses ke view 
        return view('shared.profile', compact('user', 'addresses'));
    }

    /**
     * Update profil utama user (Username, Password, dll).
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'username'  => ['sometimes', 'string', 'max:100'],
            'phone'     => ['sometimes', 'string', 'max:20'],
            'password'  => ['sometimes', 'nullable', 'confirmed', 'min:8'],
            'image'     => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Hanya ambil field yang benar-benar diisi (tidak null/kosong) 
        $data = array_filter($request->only(['username', 'phone', 'password']), function ($value) {
            return $value !== null && $value !== '';
        });

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store(
                'users/' . Auth::id(), 'public'
            );
        }

        $this->authService->updateProfile(Auth::id(), $data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

public function saveAddress(Request $request): RedirectResponse
    {
        // 1. Validasi semua field sesuai dengan form dan model #[Fillable]
        $request->validate([
            'label'           => ['required', 'string', 'max:100'], // Rumah, Kantor, dll
            'receiver'        => ['required', 'string', 'max:100'], // Nama Penerima
            'phone'           => ['required', 'string', 'max:20'],  // Nomor Telepon
            'city'            => ['required', 'string', 'max:100'], // Kota/Kabupaten
            'province'        => ['required', 'string', 'max:100'], // Provinsi
            'postalCode'      => ['required', 'string', 'max:10'],  // Kode Pos
            'completeAddress' => ['required', 'string'],            // Alamat Lengkap
            'isDefault'       => ['sometimes', 'boolean'],          // Alamat Utama
        ]);

        // 2. Logika penentuan nilai Alamat Utama (isDefault)
        $isDefault = $request->has('isDefault') ? 1 : 0;

        // Jika user mencentang "Jadikan sebagai Alamat Utama", 
        // matikan status default alamat milik user ini yang lain terlebih dahulu
        if ($isDefault == 1) {
            UserAddress::where('idUser', Auth::id())->update(['isDefault' => 0]);
        }

        // 3. GENERATE CUSTOM ID STRING (ADR-000001)
        $lastAddress = UserAddress::orderBy('idAddress', 'desc')->first();
        $lastNumber = $lastAddress ? (int) substr($lastAddress->idAddress, 4) : 0;
        $newIdAddress = 'ADR-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        // 4. Create data ke database dengan field lengkap
        UserAddress::create([
            'idAddress'       => $newIdAddress,
            'idUser'          => Auth::id(),
            'label'           => $request->label,
            'receiver'        => $request->receiver,
            'phone'           => $request->phone,
            'city'            => $request->city,
            'province'        => $request->province,
            'postalCode'      => $request->postalCode,
            'completeAddress' => $request->completeAddress,
            'isDefault'       => $isDefault,
        ]);

        return back()->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui alamat milik user (Update Alamat).
     */
    public function updateAddress(Request $request, $idAddress): RedirectResponse
    {
        // 1. Validasi semua field inputan dari form edit alamat
        $request->validate([
            'label'           => ['required', 'string', 'max:100'],
            'receiver'        => ['required', 'string', 'max:100'],
            'phone'           => ['required', 'string', 'max:20'],
            'city'            => ['required', 'string', 'max:100'],
            'province'        => ['required', 'string', 'max:100'],
            'postalCode'      => ['required', 'string', 'max:10'],
            'completeAddress' => ['required', 'string'],
            'isDefault'       => ['sometimes', 'boolean'],
        ]);

        // 2. Pastikan data alamat memang benar milik user yang sedang login
        $address = UserAddress::where('idUser', Auth::id())
                              ->where('idAddress', $idAddress)
                              ->firstOrFail();

        $isDefault = $request->has('isDefault') ? 1 : 0;

        // Jika alamat ini diubah menjadi alamat utama, reset yang lainnya
        if ($isDefault == 1) {
            UserAddress::where('idUser', Auth::id())->update(['isDefault' => 0]);
        }

        // 3. Update SEMUA field agar perubahan tersimpan seutuhnya ke DB
        $address->update([
            'label'           => $request->label,
            'receiver'        => $request->receiver,
            'phone'           => $request->phone,
            'city'            => $request->city,
            'province'        => $request->province,
            'postalCode'      => $request->postalCode,
            'completeAddress' => $request->completeAddress,
            'isDefault'       => $isDefault,
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat milik user (Hapus Alamat).
     */
    public function destroyAddress($idAddress): RedirectResponse
    {
        $address = UserAddress::where('idUser', Auth::id())
                              ->where('idAddress', $idAddress)
                              ->firstOrFail();
                              
        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}