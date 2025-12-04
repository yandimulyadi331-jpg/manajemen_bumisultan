<?php
// Test script untuk verifikasi perbaikan

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;

echo "=== VERIFICATION: Perbaikan Error ===\n\n";

// 1. Test find() berhasil
echo "1️⃣  Test find() untuk setiap ID:\n";
for ($i = 1; $i <= 10; $i++) {
    $jamaah = YayasanMasar::find($i);
    if ($jamaah) {
        echo "   ✅ ID $i → {$jamaah->nama}\n";
    } else {
        echo "   ❌ ID $i → NOT FOUND\n";
    }
}

// 2. Test query dengan where status_aktif = 1
echo "\n2️⃣  Test Query (where status_aktif = 1):\n";
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get(['id', 'kode_yayasan', 'nama', 'no_hp']);

echo "   Total available: " . $jamaahList->count() . "\n";
foreach ($jamaahList as $jamaah) {
    echo "   - {$jamaah->id}: {$jamaah->nama}\n";
}

// 3. Simulate form submit
echo "\n3️⃣  Simulasi Form Submit (tipe_penerima='jamaah', jamaah_id=1):\n";
$testJamaahId = 1;
$jamaah = YayasanMasar::find($testJamaahId);

if (!$jamaah) {
    echo "   ❌ GAGAL: Jamaah tidak ditemukan\n";
} else {
    echo "   ✅ BERHASIL: Jamaah ditemukan\n";
    echo "      - ID: {$jamaah->id}\n";
    echo "      - Nama: {$jamaah->nama}\n";
    echo "      - Kode: {$jamaah->kode_yayasan}\n";
    echo "      - Keterangan: Jamaah: {$jamaah->nama}\n";
}

echo "\n✅ VERIFIKASI SELESAI - Siap test di form\n";
?>
