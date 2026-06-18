<x-seller-layout>
@section('page-title', 'Edit Produk')

{{-- ===================== CSS ===================== --}}
<style>
    .form-label   { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 4px; }
    .form-input   { width: 100%; border: 1px solid #D1D5DB; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; outline: none; transition: border-color .15s, box-shadow .15s; }
    .form-input:focus { border-color: #008B81; box-shadow: 0 0 0 2px #E0F2F1; }
    .form-error   { font-size: 0.75rem; color: #DC2626; margin-top: 3px; }
    .section-card { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
    .section-title { font-size: 0.9375rem; font-weight: 600; color: #1F2937; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #F3F4F6; }
    .image-preview { position: relative; width: 96px; height: 96px; border-radius: 8px; overflow: hidden; border: 1px solid #E5E7EB; }
    .image-preview img { width: 100%; height: 100%; object-fit: cover; }
    .image-preview .remove-btn { position: absolute; top: 4px; right: 4px; background: rgba(0,0,0,.5); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; }
    .upload-area  { border: 2px dashed #D1D5DB; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: border-color .15s; }
    .upload-area:hover { border-color: #008B81; }
    .upload-area.dragover { border-color: #008B81; background: #E0F2F1; }
    .variant-row  { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; margin-bottom: 8px; }
    .btn-primary  { background: #008B81; color: white; }
    .btn-primary:hover { background: #00736B; }
    .btn-outline  { border: 1px solid #008B81; color: #008B81; }
    .btn-outline:hover { background: #E0F2F1; }
    .existing-img { position: relative; width: 96px; height: 96px; }
    .existing-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #E5E7EB; }
    .existing-img .primary-badge { position: absolute; bottom: 0; left: 0; right: 0; text-align: center; font-size: 0.6rem; padding: 2px; background: rgba(0,139,129,.8); color: white; border-radius: 0 0 8px 8px; }
    .existing-img .delete-check { position: absolute; top: 4px; right: 4px; }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('seller.products.index') }}" class="hover:text-nusa transition">Kelola Produk</a>
    <span>/</span>
    <span class="text-gray-800 font-medium">Edit Produk</span>
</div>

<form action="{{ route('seller.products.update', $product->idProduct) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Kolom kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Informasi dasar --}}
            <div class="section-card">
                <h2 class="section-title">Informasi Produk</h2>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="product_name"
                            value="{{ old('product_name', $product->productName) }}"
                            class="form-input @error('product_name') border-red-400 @enderror">
                        @error('product_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="form-input resize-none @error('description') border-red-400 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Berat (gram) <span class="text-red-500">*</span></label>
                            <input type="number" name="weight_gram" min="1"
                                value="{{ old('weight_gram', $product->weightGram) }}"
                                class="form-input @error('weight_gram') border-red-400 @enderror">
                            @error('weight_gram') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Status Produk</label>
                            <select name="product_status" class="form-input">
                                <option value="ACTIVE" {{ old('product_status', $product->productStatus) === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                                <option value="INACTIVE" {{ old('product_status', $product->productStatus) === 'INACTIVE' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto produk --}}
            <div class="section-card">
                <h2 class="section-title">Foto Produk</h2>

                {{-- Foto yang sudah ada --}}
                @if($product->productImages->isNotEmpty())
                    <p class="text-xs text-gray-500 mb-2">Foto saat ini — centang untuk hapus:</p>
                    <div class="flex flex-wrap gap-3 mb-4">
                        @foreach($product->productImages as $image)
                            <div class="existing-img">
                                <img src="{{ Storage::url($image->imageURL) }}" alt="foto produk">
                                @if($image->isPrimary)
                                    <span class="primary-badge">Utama</span>
                                @endif
                                <label class="delete-check cursor-pointer">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->idImage }}"
                                        class="w-4 h-4 rounded accent-red-500">
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upload foto baru --}}
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('imageInput').click()">
                    <svg class="w-7 h-7 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <p class="text-sm text-gray-500">Tambah foto baru</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP · Maks 2MB</p>
                    <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                </div>
                <div id="newImagePreview" class="flex flex-wrap gap-3 mt-3"></div>
            </div>

            {{-- Varian produk --}}
            <div class="section-card">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-800">Varian & Harga</h2>
                    <button type="button" onclick="addVariant()"
                        class="btn-outline flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                        + Tambah Varian
                    </button>
                </div>

                {{-- Varian existing --}}
                @foreach($product->productItems as $item)
                    <div class="variant-row" id="existing-variant-{{ $item->idItem }}">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 grid grid-cols-2 gap-3">
                                @foreach($item->productVariations as $variation)
                                    <div>
                                        <label class="form-label text-xs">Tipe Variasi</label>
                                        <input type="text" name="existing_variants[{{ $item->idItem }}][type]"
                                            value="{{ $variation->typeVariation }}" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Nilai</label>
                                        <input type="text" name="existing_variants[{{ $item->idItem }}][value]"
                                            value="{{ $variation->value }}" class="form-input text-sm">
                                    </div>
                                @endforeach
                                <div>
                                    <label class="form-label text-xs">Harga (Rp)</label>
                                    <input type="number" name="existing_variants[{{ $item->idItem }}][price]"
                                        value="{{ $item->price }}" min="0" class="form-input text-sm">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Stok</label>
                                    <input type="number" name="existing_variants[{{ $item->idItem }}][stock]"
                                        value="{{ $item->stock }}" min="0" class="form-input text-sm">
                                </div>
                                <div>
                                    <label class="form-label text-xs">SKU</label>
                                    <input type="text" name="existing_variants[{{ $item->idItem }}][sku]"
                                        value="{{ $item->sku }}" class="form-input text-sm">
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                        <input type="checkbox" name="existing_variants[{{ $item->idItem }}][is_active]"
                                            {{ $item->isActive ? 'checked' : '' }}
                                            class="rounded accent-nusa">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Varian baru --}}
                <div id="variantContainer"></div>
            </div>
        </div>

        {{-- Kolom kanan (1/3) --}}
        <div class="space-y-4">

            {{-- Subkategori --}}
            <div class="section-card">
                <h2 class="section-title">Kategori</h2>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @php $selectedSubs = $product->subCategories->pluck('idSubCategory')->toArray(); @endphp
                    @foreach($subCategories->groupBy('category.categoryName') as $categoryName => $subs)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                {{ $categoryName }}
                            </p>
                            <div class="space-y-1">
                                @foreach($subs as $sub)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="sub_category_ids[]"
                                            value="{{ $sub->idSubCategory }}"
                                            class="rounded text-nusa focus:ring-nusa"
                                            {{ in_array($sub->idSubCategory, old('sub_category_ids', $selectedSubs)) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $sub->subCategoryName }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('sub_category_ids') <p class="form-error mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol aksi --}}
            <div class="section-card">
                <button type="submit"
                    class="btn-primary w-full py-2.5 rounded-lg text-sm font-medium transition mb-2">
                    Simpan Perubahan
                </button>
                <a href="{{ route('seller.products.index') }}"
                    class="block text-center w-full py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>

{{-- ===================== JS ===================== --}}
<script>
    // ---- Preview foto baru ----
    let newFiles = [];

    document.getElementById('imageInput').addEventListener('change', function(e) {
        handleFiles(Array.from(e.target.files));
    });

    const uploadArea = document.getElementById('uploadArea');
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(Array.from(e.dataTransfer.files));
    });

    function handleFiles(files) {
        files.forEach(file => {
            if (!['image/jpeg','image/png','image/webp'].includes(file.type)) return;
            newFiles.push(file);
            const reader = new FileReader();
            const idx = newFiles.length - 1;
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.id = `new-preview-${idx}`;
                div.innerHTML = `
                    <img src="${e.target.result}">
                    <span class="remove-btn" onclick="removeNewImage(${idx})">✕</span>
                `;
                document.getElementById('newImagePreview').appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        syncFileInput();
    }

    function removeNewImage(idx) {
        newFiles.splice(idx, 1);
        document.getElementById('newImagePreview').innerHTML = '';
        newFiles.forEach((f, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.id = `new-preview-${i}`;
                div.innerHTML = `<img src="${e.target.result}"><span class="remove-btn" onclick="removeNewImage(${i})">✕</span>`;
                document.getElementById('newImagePreview').appendChild(div);
            };
            reader.readAsDataURL(f);
        });
        syncFileInput();
    }

    function syncFileInput() {
        const dt = new DataTransfer();
        newFiles.forEach(f => dt.items.add(f));
        document.getElementById('imageInput').files = dt.files;
    }

    // ---- Tambah varian baru ----
    let variantCount = 0;

    function addVariant() {
        const idx = variantCount++;
        const div = document.createElement('div');
        div.className = 'variant-row';
        div.id = `new-variant-${idx}`;
        div.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-1 grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Tipe Variasi</label>
                        <input type="text" name="variants[${idx}][type]" placeholder="Contoh: Warna" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Nilai</label>
                        <input type="text" name="variants[${idx}][value]" placeholder="Contoh: Merah" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Harga (Rp) *</label>
                        <input type="number" name="variants[${idx}][price]" placeholder="0" min="0" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Stok *</label>
                        <input type="number" name="variants[${idx}][stock]" placeholder="0" min="0" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">SKU</label>
                        <input type="text" name="variants[${idx}][sku]" placeholder="Opsional" class="form-input text-sm">
                    </div>
                </div>
                <button type="button" onclick="this.closest('.variant-row').remove()"
                    class="mt-5 text-gray-400 hover:text-red-500 transition text-lg">✕</button>
            </div>
        `;
        document.getElementById('variantContainer').appendChild(div);
    }
</script>

</x-seller-layout>