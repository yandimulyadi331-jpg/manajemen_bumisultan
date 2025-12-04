<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PresensiYayasan;
use App\Models\YayasanMasar;
use App\Models\Jamkerja;

echo "=== TEST Submit All Masuk Logic ===\n\n";

// Get default jam kerja
$default_jam_kerja = Jamkerja::first();
if (!$default_jam_kerja) {
    echo "❌ Tidak ada jam kerja di sistem\n";
    exit;
}

echo "Default Jam Kerja: {$default_jam_kerja->kode_jam_kerja} - {$default_jam_kerja->nama_jam_kerja}\n\n";

// Get sample yayasan
$yayasan = YayasanMasar::where('status_aktif', 1)->first();
if (!$yayasan) {
    echo "❌ Tidak ada yayasan aktif\n";
    exit;
}

$tanggal = date('Y-m-d');
$jam_in = $tanggal . ' 06:30:00';
$kode_jam_kerja = $default_jam_kerja->kode_jam_kerja;

echo "TEST 1: Create PresensiYayasan dengan kode_jam_kerja default\n";
echo "Data:\n";
echo "  - kode_yayasan: {$yayasan->kode_yayasan}\n";
echo "  - tanggal: $tanggal\n";
echo "  - jam_in: $jam_in\n";
echo "  - kode_jam_kerja: $kode_jam_kerja\n";

try {
    $presensi = PresensiYayasan::create([
        'kode_yayasan' => $yayasan->kode_yayasan,
        'tanggal' => $tanggal,
        'jam_in' => $jam_in,
        'kode_jam_kerja' => $kode_jam_kerja,
        'status' => 'h',
        'foto_in' => null
    ]);
    echo "\n✅ Berhasil insert presensi\n";
    echo "   ID: {$presensi->id}\n";
    echo "   jam_in: {$presensi->jam_in}\n";
    
    // Clean up
    $presensi->delete();
    echo "   (Deleted for testing)\n\n";
} catch (\Exception $e) {
    echo "\n❌ Error: {$e->getMessage()}\n\n";
}

// TEST 2: Test dengan jam_out (submit all pulang)
echo "TEST 2: Create PresensiYayasan untuk submit all pulang\n";
$jam_out = $tanggal . ' 17:00:00';
echo "  - jam_out: $jam_out\n";

try {
    $presensi = PresensiYayasan::create([
        'kode_yayasan' => $yayasan->kode_yayasan,
        'tanggal' => $tanggal,
        'jam_out' => $jam_out,
        'kode_jam_kerja' => $kode_jam_kerja,
        'status' => 'h',
        'foto_out' => null
    ]);
    echo "✅ Berhasil insert presensi dengan jam_out\n";
    echo "   ID: {$presensi->id}\n";
    echo "   jam_out: {$presensi->jam_out}\n";
    
    // Clean up
    $presensi->delete();
    echo "   (Deleted for testing)\n\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

echo "=== SELESAI ===\n";
?>
