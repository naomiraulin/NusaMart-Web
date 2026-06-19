<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            [
                'idSubCategory'   => 'SUB-000001',
                'idCategory'      => 'CAT-000001',
                'subCategoryName' => 'Batik',
                'description'     => 'Batik tulis dan cap lokal',
            ],
            [
                'idSubCategory'   => 'SUB-000002',
                'idCategory'      => 'CAT-000002',
                'subCategoryName' => 'Kopi',
                'description'     => 'Biji kopi asli daerah',
            ],
            ['idSubCategory' => 'SUB-000003', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Tenun & Songket', 'description' => 'Kain tenun dan songket tradisional nusantara'],
            ['idSubCategory' => 'SUB-000004', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Camilan & Keripik', 'description' => 'Camilan khas daerah dan keripik olahan'],
            ['idSubCategory' => 'SUB-000005', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Frozen Food Lokal', 'description' => 'Makanan siap saji khas Nusantara yang dibekukan'],
            ['idSubCategory' => 'SUB-000006', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Anyaman & Gerabah', 'description' => 'Kerajinan tangan dari bambu, rotan, dan tanah liat'],
            ['idSubCategory' => 'SUB-000007', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Peralatan Kayu', 'description' => 'Furnitur estetik dan alat dari kayu jati/mahoni'],
            ['idSubCategory' => 'SUB-000008', 'idCategory' => 'CAT-000004', 'subCategoryName' => 'Perawatan Kulit Herbal', 'description' => 'Masker organik, lulur, dan sabun herbal tanpa kimia'],
            ['idSubCategory' => 'SUB-000009', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Pakan Ternak & Ikan', 'description' => 'Pakan berkualitas tinggi untuk peternakan dan perikanan'],

            // --- EKSPANSI FASHION LOKAL (CAT-000001) ---
            ['idSubCategory' => 'SUB-000010', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Pakaian Pria', 'description' => 'Kemeja, kaos, dan celana buatan pengrajin lokal'],
            ['idSubCategory' => 'SUB-000011', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Pakaian Wanita', 'description' => 'Blouse, dress, dan atasan wanita lokal'],
            ['idSubCategory' => 'SUB-000012', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Sepatu & Sandal', 'description' => 'Alas kaki kulit dan sintetis handmade'],
            ['idSubCategory' => 'SUB-000013', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Tas & Dompet', 'description' => 'Tas anyaman, kulit, dan kanvas lokal'],
            ['idSubCategory' => 'SUB-000014', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Aksesoris Fashion', 'description' => 'Topi, sabuk, dan kacamata kayu'],
            ['idSubCategory' => 'SUB-000015', 'idCategory' => 'CAT-000001', 'subCategoryName' => 'Perhiasan Handmade', 'description' => 'Kalung, gelang, cincin buatan pengrajin perak/batu'],

            // --- EKSPANSI KULINER NUSANTARA (CAT-000002) ---
            ['idSubCategory' => 'SUB-000016', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Bumbu & Rempah', 'description' => 'Bumbu dapur, sambal botolan, dan rempah kering'],
            ['idSubCategory' => 'SUB-000017', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Minuman Tradisional', 'description' => 'Jamu instan, sirup lokal, dan teh herbal'],
            ['idSubCategory' => 'SUB-000018', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Kue Tradisional & Roti', 'description' => 'Kue basah, bolu, dan roti buatan rumahan'],
            ['idSubCategory' => 'SUB-000019', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Makanan Kering', 'description' => 'Abon, dendeng, dan teri krispi'],
            ['idSubCategory' => 'SUB-000020', 'idCategory' => 'CAT-000002', 'subCategoryName' => 'Madu & Gula Lokal', 'description' => 'Madu murni, nektar, dan gula aren organik'],

            // --- EKSPANSI KERAJINAN TANGAN (CAT-000003) ---
            ['idSubCategory' => 'SUB-000021', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Rajut & Sulam', 'description' => 'Pakaian dan aksesoris hasil rajutan tangan'],
            ['idSubCategory' => 'SUB-000022', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Keramik & Kaca', 'description' => 'Piring, gelas, dan vas dari keramik buatan tangan'],
            ['idSubCategory' => 'SUB-000023', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Barang Daur Ulang', 'description' => 'Produk fungsional estetik dari barang bekas'],
            ['idSubCategory' => 'SUB-000024', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Ukiran Kayu & Batu', 'description' => 'Pajangan dan seni ukir khas daerah'],
            ['idSubCategory' => 'SUB-000025', 'idCategory' => 'CAT-000003', 'subCategoryName' => 'Batik Kayu & Topeng', 'description' => 'Topeng tradisional dan kerajinan batik di media kayu'],

            // --- EKSPANSI KESEHATAN & KECANTIKAN (CAT-000004) ---
            ['idSubCategory' => 'SUB-000026', 'idCategory' => 'CAT-000004', 'subCategoryName' => 'Minyak Esensial', 'description' => 'Aromaterapi dan minyak atsiri murni lokal'],
            ['idSubCategory' => 'SUB-000027', 'idCategory' => 'CAT-000004', 'subCategoryName' => 'Sabun & Sampo Organik', 'description' => 'Sabun mandi dan sampo berbahan dasar alam'],
            ['idSubCategory' => 'SUB-000028', 'idCategory' => 'CAT-000004', 'subCategoryName' => 'Suplemen Tradisional', 'description' => 'Kapsul herbal, ekstrak daun, dan akar pengobatan'],
            ['idSubCategory' => 'SUB-000029', 'idCategory' => 'CAT-000004', 'subCategoryName' => 'Alat Pijat & Relaksasi', 'description' => 'Alat pijat refleksi dari kayu atau batu'],

            // --- EKSPANSI AGROBISNIS & PERIKANAN (CAT-000005) ---
            ['idSubCategory' => 'SUB-000030', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Benih & Bibit', 'description' => 'Bibit tanaman sayur, buah, dan hias'],
            ['idSubCategory' => 'SUB-000031', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Pupuk & Media Tanam', 'description' => 'Kompos organik, cocopeat, dan pupuk cair'],
            ['idSubCategory' => 'SUB-000032', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Sayur & Buah Segar', 'description' => 'Hasil panen langsung dari petani lokal'],
            ['idSubCategory' => 'SUB-000033', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Ikan & Daging Olahan', 'description' => 'Ikan asap, daging unggas segar, dan telur'],
            ['idSubCategory' => 'SUB-000034', 'idCategory' => 'CAT-000005', 'subCategoryName' => 'Alat Pertanian', 'description' => 'Cangkul, sabit, dan perlengkapan berkebun UMKM'],

            // --- RUMAH TANGGA & DEKORASI (CAT-000006) ---
            ['idSubCategory' => 'SUB-000035', 'idCategory' => 'CAT-000006', 'subCategoryName' => 'Sprei & Selimut', 'description' => 'Sprei kain katun lokal dan selimut tenun'],
            ['idSubCategory' => 'SUB-000036', 'idCategory' => 'CAT-000006', 'subCategoryName' => 'Peralatan Dapur', 'description' => 'Sodet kayu, panci tanah liat, dsb.'],
            ['idSubCategory' => 'SUB-000037', 'idCategory' => 'CAT-000006', 'subCategoryName' => 'Lampu & Hiasan', 'description' => 'Lampu gantung bambu dan makrame dinding'],
            ['idSubCategory' => 'SUB-000038', 'idCategory' => 'CAT-000006', 'subCategoryName' => 'Karpet & Keset', 'description' => 'Keset kain perca dan karpet anyaman tikar'],

            // --- KESENIAN & ALAT MUSIK (CAT-000007) ---
            ['idSubCategory' => 'SUB-000039', 'idCategory' => 'CAT-000007', 'subCategoryName' => 'Alat Musik Tradisional', 'description' => 'Gamelan, angklung, kecapi, dan sasando'],
            ['idSubCategory' => 'SUB-000040', 'idCategory' => 'CAT-000007', 'subCategoryName' => 'Gitar & Alat Petik', 'description' => 'Gitar akustik buatan pengrajin lokal'],
            ['idSubCategory' => 'SUB-000041', 'idCategory' => 'CAT-000007', 'subCategoryName' => 'Lukisan & Kaligrafi', 'description' => 'Karya seni rupa dan kaligrafi tangan'],
            ['idSubCategory' => 'SUB-000042', 'idCategory' => 'CAT-000007', 'subCategoryName' => 'Perlengkapan Tari', 'description' => 'Selendang, siger, dan aksesoris tari daerah'],

            // --- SUVENIR & PERNIKAHAN (CAT-000008) ---
            ['idSubCategory' => 'SUB-000043', 'idCategory' => 'CAT-000008', 'subCategoryName' => 'Undangan Kertas Daun', 'description' => 'Undangan rustic dan ramah lingkungan'],
            ['idSubCategory' => 'SUB-000044', 'idCategory' => 'CAT-000008', 'subCategoryName' => 'Suvenir Pesta', 'description' => 'Gantungan kunci, kipas, dan pouch mini'],
            ['idSubCategory' => 'SUB-000045', 'idCategory' => 'CAT-000008', 'subCategoryName' => 'Parsel & Hampers', 'description' => 'Paket hantaran hari raya produk UMKM'],
            ['idSubCategory' => 'SUB-000046', 'idCategory' => 'CAT-000008', 'subCategoryName' => 'Buket Custom', 'description' => 'Buket bunga kering, snack, atau uang'],

            // --- MAINAN & HOBI (CAT-000009) ---
            ['idSubCategory' => 'SUB-000047', 'idCategory' => 'CAT-000009', 'subCategoryName' => 'Mainan Kayu Edukasi', 'description' => 'Puzzle kayu dan balok susun anak'],
            ['idSubCategory' => 'SUB-000048', 'idCategory' => 'CAT-000009', 'subCategoryName' => 'Mainan Tradisional', 'description' => 'Gasing, congklak, dan layang-layang'],
            ['idSubCategory' => 'SUB-000049', 'idCategory' => 'CAT-000009', 'subCategoryName' => 'Miniatur & Diorama', 'description' => 'Miniatur kendaraan dan die-cast custom lokal'],

            // --- BUKU & ALAT TULIS (CAT-000010) ---
            ['idSubCategory' => 'SUB-000050', 'idCategory' => 'CAT-000010', 'subCategoryName' => 'Buku Indie Lokal', 'description' => 'Buku terbitan penerbit independen'],
            ['idSubCategory' => 'SUB-000051', 'idCategory' => 'CAT-000010', 'subCategoryName' => 'Buku Catatan Custom', 'description' => 'Jurnal dan notebook jilid tangan'],

            // --- PERLENGKAPAN IBU & BAYI (CAT-000011) ---
            ['idSubCategory' => 'SUB-000052', 'idCategory' => 'CAT-000011', 'subCategoryName' => 'Pakaian Bayi', 'description' => 'Baju bayi rajut dan bahan katun organik'],
            ['idSubCategory' => 'SUB-000053', 'idCategory' => 'CAT-000011', 'subCategoryName' => 'Gendongan Tradisional', 'description' => 'Jarik bayi dan kain gendong nyaman'],

            // --- PERLENGKAPAN IBADAH (CAT-000012) ---
            ['idSubCategory' => 'SUB-000054', 'idCategory' => 'CAT-000012', 'subCategoryName' => 'Pakaian Ibadah', 'description' => 'Mukena, sarung tenun, dan peci lokal'],
            ['idSubCategory' => 'SUB-000055', 'idCategory' => 'CAT-000012', 'subCategoryName' => 'Alat Ibadah', 'description' => 'Sajadah anyam, tasbih kayu, dan dupa'],

            // --- OTOMOTIF & AKSESORI (CAT-000013) ---
            ['idSubCategory' => 'SUB-000056', 'idCategory' => 'CAT-000013', 'subCategoryName' => 'Aksesoris Kendaraan', 'description' => 'Jok kulit motor custom dan stiker lokal'],

            // --- BAHAN BAKU UMKM (CAT-000014) ---
            ['idSubCategory' => 'SUB-000057', 'idCategory' => 'CAT-000014', 'subCategoryName' => 'Kain & Benang', 'description' => 'Kain meteran dan benang jahit/rajut untuk produksi UMKM'],
        ];

        foreach ($subCategories as $sub) {
            SubCategory::create($sub);
        }
    }
}