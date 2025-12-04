<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PresensiYayasan;
use App\Models\YayasanMasar;

echo "=== TEST DATETIME FORMAT FIX PresensiYayasan ===\n\n";

// Get active yayasan
$yayasan = YayasanMasar::where('status_aktif', 1)->first(['kode_yayasan', 'nama']);

if (!$yayasan) {
    echo "❌ Tidak ada yayasan aktif\n";
    exit;
}

$tanggal = date('Y-m-d');
echo "Yayasan: {$yayasan->nama} ({$yayasan->kode_yayasan})\n";
echo "Tanggal: $tanggal\n\n";

// Test 1: Insert dengan jam_in format DATETIME lengkap
echo "TEST 1: Insert dengan format DATETIME lengkap (2025-12-03 14:30:00)\n";
try {
    // Get kode_jam_kerja untuk avoid constraint error
    $jamkerja = \App\Models\Jamkerja::first();
    $kode_jam_kerja = $jamkerja ? $jamkerja->kode_jam_kerja : '001';
    
    $jam_in = $tanggal . ' 14:30:00';
    $test1 = PresensiYayasan::create([
        'kode_yayasan' => $yayasan->kode_yayasan,
        'tanggal' => $tanggal,
        'jam_in' => $jam_in,
        'kode_jam_kerja' => $kode_jam_kerja,
        'status' => 'h',
        'foto_in' => null
    ]);
    echo "✅ Berhasil insert: jam_in=$jam_in\n";
    echo "   ID: {$test1->id}\n\n";
    $test1->delete();
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

// Test 2: Insert dengan jam_in format TIME saja (seperti error sebelumnya)
echo "TEST 2: Insert dengan format TIME saja (05:27:31) - harusnya tetap error\n";
try {
    $jam_in_time = '05:27:31';
    $test2 = PresensiYayasan::create([
        'kode_yayasan' => $yayasan->kode_yayasan,
        'tanggal' => $tanggal,
        'jam_in' => $jam_in_time,
        'kode_jam_kerja' => null,
        'status' => 'h',
        'foto_in' => null
    ]);
    echo "❌ ERROR: Berhasil insert dengan TIME format saja (seharusnya gagal)\n";
    $test2->delete();
} catch (\Exception $e) {
    echo "✅ Correctly rejected TIME format: " . substr($e->getMessage(), 0, 80) . "...\n\n";
}

// Test 3: Update dengan format DATETIME
echo "TEST 3: Update jam_in dengan format DATETIME\n";
try {
    // Get kode_jam_kerja untuk avoid constraint error
    $jamkerja = \App\Models\Jamkerja::first();
    $kode_jam_kerja = $jamkerja ? $jamkerja->kode_jam_kerja : '001';
    
    $existing = PresensiYayasan::where('kode_yayasan', $yayasan->kode_yayasan)
        ->where('tanggal', $tanggal)
        ->first();
    
    if (!$existing) {
        $existing = PresensiYayasan::create([
            'kode_yayasan' => $yayasan->kode_yayasan,
            'tanggal' => $tanggal,
            'kode_jam_kerja' => $kode_jam_kerja,
            'status' => 'h',
            'foto_in' => null
        ]);
    }
    
    $jam_in = $tanggal . ' 08:45:00';
    $existing->update(['jam_in' => $jam_in]);
    echo "✅ Berhasil update: jam_in=$jam_in\n";
    echo "   ID: {$existing->id}\n\n";
    $existing->delete();
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

// Test 4: Cek struktur table
echo "TEST 4: Verifikasi struktur tabel presensi_yayasan\n";
try {
    $columns = \Illuminate\Support\Facades\DB::select(
        "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE 
         FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_NAME = 'presensi_yayasan' 
         AND TABLE_SCHEMA = DATABASE()
         AND COLUMN_NAME IN ('jam_in', 'jam_out', 'tanggal')"
    );
    
    foreach ($columns as $col) {
        echo "  - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE} (nullable: {$col->IS_NULLABLE})\n";
    }
    echo "\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

echo "=== SELESAI ===\n";
?>
