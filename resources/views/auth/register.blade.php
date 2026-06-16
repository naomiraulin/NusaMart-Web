<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - NusaMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nusa: {
                            light: '#E0F2F1',
                            DEFAULT: '#008B81',
                            dark: '#00736B',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="absolute top-0 left-0 w-full bg-white shadow-sm py-4 text-center z-10">
        <h1 class="text-3xl font-bold text-nusa">NusaMart</h1>
    </div>

    <div class="w-full max-w-5xl flex flex-col md:flex-row items-start justify-center mt-24 pb-12">
        
        <div class="w-full md:w-1/2 p-8 hidden md:flex flex-col items-center text-center sticky top-24">
            <div class="w-64 h-64 mb-6">
                <img src="https://via.placeholder.com/250x250.png?text=Ilustrasi+Belanja" alt="Ilustrasi" class="w-full h-full object-contain">
            </div>
            <h2 class="text-xl font-bold text-nusa-dark">Dukung Produk Lokal Kebanggaanmu!</h2>
        </div>

        <div class="w-full md:w-5/12 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar</h2>
                <p class="text-sm text-gray-500 mt-1">Sudah punya akun? <a href="{{ route('login') }}" class="text-nusa font-semibold hover:underline">Log In</a></p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-3 rounded-md text-sm mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required 
                    class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition">
                
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
                    class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition">
                
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Nomor HP" required 
                    class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition">
                
                <input type="password" name="password" placeholder="Password (Min. 8 Karakter)" required 
                    class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition">

                <select name="role" id="roleSelect" required class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition bg-white text-gray-700">
                    <option value="" disabled selected>Pilih Peran Akun</option>
                    <option value="BUYER" {{ old('role') == 'BUYER' ? 'selected' : '' }}>Pembeli (Buyer)</option>
                    <option value="SELLER" {{ old('role') == 'SELLER' ? 'selected' : '' }}>Penjual (Seller)</option>
                </select>

                <div id="sellerFields" class="hidden space-y-4 bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                    <p class="text-sm font-semibold text-gray-700">Lengkapi Data Toko</p>
                    
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="NIK (16 Digit)" 
                        class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition text-sm">
                    
                    <input type="text" name="bankName" value="{{ old('bankName') }}" placeholder="Nama Bank (Contoh: BCA, Mandiri)" 
                        class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition text-sm">
                    
                    <input type="text" name="accountNumber" value="{{ old('accountNumber') }}" placeholder="Nomor Rekening" 
                        class="w-full border border-gray-300 rounded-md p-3 focus:ring-1 focus:ring-nusa focus:border-nusa outline-none transition text-sm">
                </div>
                
                <button type="submit" class="w-full bg-nusa hover:bg-nusa-dark text-white font-bold py-3 rounded-md transition mt-6">Daftar</button>
            </form>
        </div>

    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const sellerFields = document.getElementById('sellerFields');

        // Fungsi untuk mengecek status saat halaman dimuat atau saat role diubah
        function toggleSellerFields() {
            if (roleSelect.value === 'SELLER') {
                sellerFields.classList.remove('hidden');
                // Tambahkan atribut required secara dinamis agar validasi HTML5 berjalan
                sellerFields.querySelectorAll('input').forEach(input => input.setAttribute('required', 'true'));
            } else {
                sellerFields.classList.add('hidden');
                // Hapus atribut required jika bukan seller
                sellerFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
            }
        }

        roleSelect.addEventListener('change', toggleSellerFields);
        
        // Panggil saat load (berguna jika dikembalikan karena error validasi dan old('role') adalah SELLER)
        window.addEventListener('DOMContentLoaded', toggleSellerFields);
    </script>

</body>
</html>