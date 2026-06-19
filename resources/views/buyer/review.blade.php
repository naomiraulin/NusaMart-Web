{{-- LANGSUNG GUNAKAN BUYER LAYOUT --}}
<x-buyer-layout>
    <x-slot name="pageTitle">Tulis Ulasan</x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- BACK BUTTON & HEADER --}}
        {{-- BACK BUTTON & HEADER --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ url()->previous() }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-nusa hover:border-nusa hover:bg-nusa-light transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Nilai Produk</h1>
                <p class="text-sm text-gray-500 mt-0.5">Bagikan pengalamanmu berbelanja produk ini</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            
            {{-- INFO PRODUK YANG DIULAS --}}
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                <div class="w-16 h-16 shrink-0 rounded-xl bg-white border border-gray-200 overflow-hidden">
                    @if($imageURL)
                        <img src="{{ $imageURL }}" alt="Produk" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Mengulas Barang</p>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $productName }}</h3>
                </div>
            </div>

            {{-- FORM ULASAN --}}
            <form action="{{ route('buyer.reviews.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                @csrf
                <input type="hidden" name="order_item_id" value="{{ $orderItemId }}">

                {{-- BINTANG RATING (ALPINE.JS) --}}
                <div class="mb-8 flex flex-col items-center" x-data="{ rating: 0, hoverRating: 0 }">
                    <p class="text-sm font-bold text-gray-700 mb-3">Berapa bintang untuk produk ini?</p>
                    <input type="hidden" name="rating" x-model="rating">
                    
                    <div class="flex gap-2">
                        <template x-for="i in 5">
                            <button type="button" 
                                class="focus:outline-none transition-transform hover:scale-110"
                                @click="rating = i"
                                @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = 0">
                                <svg class="w-10 h-10 transition-colors duration-200" 
                                    :class="(hoverRating >= i || rating >= i) ? 'text-amber-400 fill-current' : 'text-gray-200 fill-current'" 
                                    viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                    
                    {{-- Validasi Rating --}}
                    @error('rating')
                        <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KOMENTAR --}}
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">Tulis ulasanmu (Opsional)</label>
                    <textarea name="comment" id="comment" rows="4" 
                        placeholder="Bagaimana kualitas produk ini? Apakah sesuai dengan deskripsi?"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-nusa/30 focus:border-nusa transition-all resize-none"></textarea>
                    @error('comment')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- UNGGAH FOTO (MULTIPLE) --}}
                <div class="mb-8" x-data="{ files: [] }">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Unggah Foto (Opsional)</label>
                    
                    <div class="flex flex-wrap gap-3">
                        {{-- Tombol Custom Upload --}}
                        <label class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 hover:border-nusa hover:text-nusa hover:bg-nusa/5 cursor-pointer transition-colors">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Tambah</span>
                            {{-- Input file disembunyikan tapi berfungsi, menerima array multiple --}}
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" 
                                @change="files = Array.from($event.target.files).map(file => URL.createObjectURL(file))">
                        </label>

                        {{-- Preview Gambar yang dipilih --}}
                        <template x-for="url in files">
                            <div class="w-20 h-20 rounded-xl border border-gray-200 overflow-hidden bg-gray-100 shrink-0">
                                <img :src="url" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Bisa mengunggah lebih dari 1 foto.</p>
                    
                    @error('images.*')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TOMBOL SUBMIT --}}
                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-nusa to-nusa-dark text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-nusa/20 active:scale-[0.98] transition-all duration-200">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>
</x-buyer-layout>   