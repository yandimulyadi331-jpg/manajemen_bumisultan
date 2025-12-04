<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PresensiYayasan;
use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== TEST AUTO-INCREMENT KEHADIRAN YAYASAN MASAR ===\n\n";

// 1. Cek Dani sebelumnya
echo "1️⃣  CEK DATA DANI SEBELUM TEST:\n";
$dani = YayasanMasar::where('kode_yayasan', '251200004')->first();
echo "   Nama: " . $dani->nama . "\n";
echo "   Jumlah Kehadiran SEBELUM: " . $dani->jumlah_kehadiran . "\n\n";

// 2. Buat record presensi untuk Dani hari ini
echo "2️⃣  CREATE RECORD PRESENSI BARU:\n";
$today = date('Y-m-d');

// Cek apakah sudah ada record untuk hari ini
$existing = PresensiYayasan::where('kode_yayasan', '251200004')
    ->whereDate('tanggal', $today)
    ->first();

if ($existing) {
    echo "   ⚠️  Sudah ada record untuk hari ini, delete dulu\n";
    $existing->delete();
}

// Create record baru
$presensi = PresensiYayasan::create([
    'kode_yayasan' => '251200004',
    'tanggal' => $today,
    'jam_in' => now(),
    'kode_jam_kerja' => 'JK01',
    'status' => 'h'
]);

echo "   ✅ Record presensi created:\n";
echo "      ID: " . $presensi->id . "\n";
echo "      Tanggal: " . $presensi->tanggal . "\n";
echo "      Jam In: " . $presensi->jam_in . "\n\n";

// 3. Cek Dani setelah create
echo "3️⃣  CEK DATA DANI SETELAH CREATE:\n";
$dani->refresh(); // Refresh dari database
echo "   Jumlah Kehadiran SESUDAH: " . $dani->jumlah_kehadiran . "\n";

if ($dani->jumlah_kehadiran > 0) {
    echo "   ✅ AUTO-INCREMENT BERHASIL! Kehadiran bertambah.\n";
} else {
    echo "   ❌ AUTO-INCREMENT GAGAL! Kehadiran tidak bertambah.\n";
}

echo "\n";

// 4. Cek di database
echo "4️⃣  CEK DI DATABASE:\n";
$dbRecord = DB::table('yayasan_masar')
    ->where('kode_yayasan', '251200004')
    ->first();

echo "   Query langsung: jumlah_kehadiran = " . $dbRecord->jumlah_kehadiran . "\n";

echo "\n";

// 5. Cek log
echo "5️⃣  CEK PRESENSI RECORDS:\n";
$presensi_records = PresensiYayasan::where('kode_yayasan', '251200004')
    ->orderBy('tanggal', 'desc')
    ->limit(5)
    ->get();

echo "   Total presensi: " . $presensi_records->count() . "\n";
foreach ($presensi_records as $p) {
    $jam_in = $p->jam_in ? (is_object($p->jam_in) ? $p->jam_in->format('H:i') : $p->jam_in) : 'null';
    echo "      - " . $p->tanggal . ": jam_in=" . $jam_in . "\n";
}

?>
