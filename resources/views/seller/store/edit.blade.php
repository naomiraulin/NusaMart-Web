<x-seller-layout>
    <x-slot name="pageTitle">Edit Profil Toko</x-slot>

    {{-- CSS Kustom di bagian atas --}}
    <style>
        .custom-file-upload {
            border: 2px dashed #D1D5DB;
            transition: border-color 0.3s ease;
        }
        .custom-file-upload:hover {
            border-color: #008B81; /* Warna nusa */
        }
    </style>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        
        <form action="{{ route('seller.store.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Upload Logo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Toko</label>
                <div class="flex items-center gap-6">
                    <div id="image-preview-container" class="w-24 h-24 rounded-full overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0">
                        @if($store->logoURL)
                            <img id="image-preview" src="{{ Storage::url($store->logoURL) }}" alt="Preview" class="w-full h-full object-cover">
                        @else
                            <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="image-placeholder" class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-grow">
                        <label for="logo" class="cursor-pointer custom-file-upload block w-full py-4 px-4 text-center rounded-md bg-gray-50">
                            <span class="text-sm text-gray-500">Klik untuk unggah logo (Max 2MB, JPG/PNG)</span>
                            <input type="file" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">
                        </label>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Nama Toko --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                <input type="text" id="name" name="name" value="{{ old('name', $store->name) }}" 
                       class="w-full px-4 py-2 border rounded-md focus:ring-nusa focus:border-nusa @error('name') border-red-500 @else border-gray-300 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko</label>
                <textarea id="description" name="description" rows="4" 
                          class="w-full px-4 py-2 border rounded-md focus:ring-nusa focus:border-nusa @error('description') border-red-500 @else border-gray-300 @enderror">{{ old('description', $store->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Lokasi & URL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Alamat Singkat / Kota</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $store->location) }}" 
                           class="w-full px-4 py-2 border rounded-md focus:ring-nusa focus:border-nusa @error('location') border-red-500 @else border-gray-300 @enderror">
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="url_location" class="block text-sm font-medium text-gray-700 mb-1">URL Google Maps</label>
                    <input type="url" id="url_location" name="url_location" value="{{ old('url_location', $store->urlLocation) }}" placeholder="https://goo.gl/maps/..."
                           class="w-full px-4 py-2 border rounded-md focus:ring-nusa focus:border-nusa @error('url_location') border-red-500 @else border-gray-300 @enderror">
                    @error('url_location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 bg-nusa text-white text-sm font-medium rounded-md hover:bg-nusa-dark transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('seller.store.show') }}" class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>

    {{-- JS di bagian bawah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');

            // Logika untuk Image Preview saat file dipilih
            logoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        if (imagePlaceholder) {
                            imagePlaceholder.classList.add('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</x-seller-layout>