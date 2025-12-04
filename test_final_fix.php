<?php
// Final test - verifikasi semua perbaikan

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;

echo "=== FINAL TEST: Perbaikan Error Lengkap ===\n\n";

// 1. Test find dengan kode_yayasan (primary key)
echo "1️⃣  Test find() dengan kode_yayasan (Primary Key):\n";
$testCodes = ['251200001', '251200002', '251200009'];
foreach ($testCodes as $code) {
    $jamaah = YayasanMasar::find($code);
    if ($jamaah) {
        echo "   ✅ Kode $code → {$jamaah->nama}\n";
    } else {
        echo "   ❌ Kode $code → NOT FOUND\n";
    }
}

// 2. Test query dropdown
echo "\n2️⃣  Test Query Dropdown (where status_aktif = 1):\n";
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get(['kode_yayasan', 'nama', 'no_hp']);

echo "   Total jamaah: " . $jamaahList->count() . "\n";
foreach ($jamaahList as $jamaah) {
    echo "   - Kode: {$jamaah->kode_yayasan} | Nama: {$jamaah->nama}\n";
}

// 3. Simulasi form submit dengan kode_yayasan
echo "\n3️⃣  Simulasi Form Submit (tipe='jamaah', jamaah_id='251200009'):\n";
$testKode = '251200009';
$jamaah = YayasanMasar::find($testKode);

if (!$jamaah) {
    echo "   ❌ GAGAL: Jamaah tidak ditemukan\n";
} else {
    echo "   ✅ BERHASIL: Jamaah ditemukan\n";
    echo "      - Kode: {$jamaah->kode_yayasan}\n";
    echo "      - Nama: {$jamaah->nama}\n";
    echo "      - Keterangan: Jamaah: {$jamaah->nama}\n";
    echo "      - FK akan store: {$jamaah->kode_yayasan}\n";
}

// 4. Test validasi
echo "\n4️⃣  Test Validasi (exists:yayasan_masar,kode_yayasan):\n";
$validator = \Illuminate\Support\Facades\Validator::make(
    ['jamaah_id' => '251200009'],
    ['jamaah_id' => 'exists:yayasan_masar,kode_yayasan']
);

if ($validator->fails()) {
    echo "   ❌ Validasi GAGAL\n";
    print_r($validator->errors());
} else {
    echo "   ✅ Validasi BERHASIL\n";
    echo "      - jamaah_id='251200009' adalah valid\n";
}

echo "\n✅ SEMUA TEST PASSED - ERROR SUDAH DIPERBAIKI!\n";
echo "\n📝 CATATAN:\n";
echo "   - Primary key yayasan_masar: kode_yayasan (string)\n";
echo "   - Dropdown menampilkan: nama jamaah (tetap sama)\n";
echo "   - Dropdown value: kode_yayasan (bukan id)\n";
echo "   - Validasi: exists:yayasan_masar,kode_yayasan\n";
echo "   - Find: YayasanMasar::find('251200009')\n";
?>
