<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JumlahKehadiranMingguan;
use Carbon\Carbon;

echo "=== TEST KEHADIRAN MINGGUAN ===\n\n";

// Test 1: Cek apakah hari ini Jumat
echo "1. Cek Hari Ini:\n";
$today = now();
$isJumat = JumlahKehadiranMingguan::isJumat($today);
$namaHari = JumlahKehadiranMingguan::getNamaHari($today);
$mingguKe = JumlahKehadiranMingguan::getMingguKe($today);

echo "   Hari: $namaHari\n";
echo "   Tanggal: " . $today->format('Y-m-d') . "\n";
echo "   Minggu ke: $mingguKe\n";
echo "   Apakah Jumat? " . ($isJumat ? 'YA' : 'TIDAK') . "\n\n";

// Test 2: Simulasi Jumat terdekat
echo "2. Cari Jumat Terdekat:\n";
$nextFriday = $today->copy();
while (!JumlahKehadiranMingguan::isJumat($nextFriday)) {
    $nextFriday->addDay();
}
echo "   Jumat Terdekat: " . $nextFriday->format('Y-m-d (D)') . "\n";
echo "   Minggu Ke: " . JumlahKehadiranMingguan::getMingguKe($nextFriday) . "\n\n";

// Test 3: Cek existing data
echo "3. Data Kehadiran Mingguan yang Ada:\n";
$allData = JumlahKehadiranMingguan::all();
if ($allData->count() > 0) {
    foreach ($allData as $data) {
        echo "   - Jamaah ID: {$data->jamaah_id}, Tahun: {$data->tahun}, Minggu: {$data->minggu_ke}, Kehadiran: {$data->jumlah_kehadiran}\n";
    }
} else {
    echo "   (Belum ada data)\n";
}

echo "\n✓ Test selesai\n";
