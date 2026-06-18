<x-seller-layout>
@section('page-title', 'Tambah Produk')

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
    .upload-area  { border: 2px dashed #D1D5DB; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: border-color .15s; }
    .upload-area:hover { border-color: #008B81; }
    .upload-area.dragover { border-color: #008B81; background: #E0F2F1; }
    .variant-row  { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; margin-bottom: 8px; }
    .btn-primary  { background: #008B81; color: white; }
    .btn-primary:hover { background: #00736B; }
    .btn-outline  { border: 1px solid #008B81; color: #008B81; }
    .btn-outline:hover { background: #E0F2F1; }
    .sub-checkbox:checked + label { background: #E0F2F1; border-color: #008B81; color: #008B81; }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('seller.products.index') }}" class="hover:text-nusa transition">Kelola Produk</a>
    <span>/</span>
    <span class="text-gray-800 font-medium">Tambah Produk</span>
</div>

<form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Kolom kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Informasi dasar --}}
            <div class="section-card">
                <h2 class="section-title">Informasi Produk</h2>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}"
                            placeholder="Contoh: Batik Tulis Motif Parang"
                            class="form-input @error('product_name') border-red-400 @enderror">
                        @error('product_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="4"
                            placeholder="Deskripsikan produkmu secara detail..."
                            class="form-input resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Berat (gram) <span class="text-red-500">*</span></label>
                            <input type="number" name="weight_gram" value="{{ old('weight_gram') }}"
                                placeholder="Contoh: 500"
                                min="1" class="form-input @error('weight_gram') border-red-400 @enderror">
                            @error('weight_gram') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Status Produk</label>
                            <select name="product_status" class="form-input">
                                <option value="ACTIVE" {{ old('product_status') === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                                <option value="INACTIVE" {{ old('product_status') === 'INACTIVE' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto produk --}}
            <div class="section-card">
                <h2 class="section-title">Foto Produk <span class="text-sm font-normal text-gray-400">(maks 10 foto)</span></h2>

                <div id="imagePreviewContainer" class="flex flex-wrap gap-3 mb-3"></div>

                <div class="upload-area" id="uploadArea" onclick="document.getElementById('imageInput').click()">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau drag & drop foto di sini</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP · Maks 2MB per foto</p>
                    <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden">
                </div>
                @error('images') <p class="form-error mt-2">{{ $message }}</p> @enderror
                @error('images.*') <p class="form-error mt-2">{{ $message }}</p> @enderror
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

                <div id="variantContainer">
                    {{-- Diisi JS --}}
                </div>

                <p class="text-xs text-gray-400 mt-2" id="variantHint">
                    Tambahkan minimal 1 varian dengan harga dan stok.
                </p>
            </div>
        </div>

        {{-- Kolom kanan (1/3) --}}
        <div class="space-y-4">

            {{-- Subkategori --}}
            <div class="section-card">
                <h2 class="section-title">Kategori <span class="text-red-500">*</span></h2>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
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
                                            {{ in_array($sub->idSubCategory, old('sub_category_ids', [])) ? 'checked' : '' }}>
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
                    Simpan Produk
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
    // ---- Preview gambar ----
    let selectedFiles = [];

    document.getElementById('imageInput').addEventListener('change', function(e) {
        handleFiles(Array.from(e.target.files));
    });

    // Drag & drop
    const uploadArea = document.getElementById('uploadArea');
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(Array.from(e.dataTransfer.files));
    });

    function handleFiles(files) {
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        files.forEach(file => {
            if (!allowed.includes(file.type)) return;
            if (selectedFiles.length >= 10) return;
            selectedFiles.push(file);
            renderPreview(file, selectedFiles.length - 1);
        });
        syncFileInput();
    }

    function renderPreview(file, index) {
        const reader = new FileReader();
        reader.onload = e => {
            const container = document.getElementById('imagePreviewContainer');
            const div = document.createElement('div');
            div.className = 'image-preview';
            div.id = `preview-${index}`;
            div.innerHTML = `
                <img src="${e.target.result}" alt="preview">
                ${index === 0 ? '<span class="absolute bottom-0 left-0 right-0 text-center text-white text-xs py-0.5" style="background:rgba(0,139,129,.8)">Utama</span>' : ''}
                <span class="remove-btn" onclick="removeImage(${index})">✕</span>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        document.getElementById('imagePreviewContainer').innerHTML = '';
        selectedFiles.forEach((f, i) => renderPreview(f, i));
        syncFileInput();
    }

    function syncFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        document.getElementById('imageInput').files = dt.files;
    }

    // ---- Varian produk ----
    let variantCount = 0;

    function addVariant() {
        const container = document.getElementById('variantContainer');
        const idx = variantCount++;
        const div = document.createElement('div');
        div.className = 'variant-row';
        div.id = `variant-${idx}`;
        div.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-1 grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Tipe Variasi</label>
                        <input type="text" name="variants[${idx}][type]" placeholder="Contoh: Warna"
                            class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Nilai</label>
                        <input type="text" name="variants[${idx}][value]" placeholder="Contoh: Merah"
                            class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Harga (Rp) *</label>
                        <input type="number" name="variants[${idx}][price]" placeholder="0"
                            min="0" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Stok *</label>
                        <input type="number" name="variants[${idx}][stock]" placeholder="0"
                            min="0" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">SKU</label>
                        <input type="text" name="variants[${idx}][sku]" placeholder="Opsional"
                            class="form-input text-sm">
                    </div>
                </div>
                <button type="button" onclick="removeVariant(${idx})"
                    class="mt-5 text-gray-400 hover:text-red-500 transition text-lg">✕</button>
            </div>
        `;
        container.appendChild(div);
        document.getElementById('variantHint').style.display = 'none';
    }

    function removeVariant(idx) {
        document.getElementById(`variant-${idx}`).remove();
        if (document.querySelectorAll('.variant-row').length === 0) {
            document.getElementById('variantHint').style.display = '';
        }
    }

    // Tambah 1 varian default saat load
    addVariant();
</script>

</x-seller-layout>