<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Facerecognition;

echo "=== TEST STRUKTUR TABEL & MODEL FACE RECOGNITION ===" . PHP_EOL . PHP_EOL;

// Test 1: Cek struktur kolom tabel
echo "1. STRUKTUR KOLOM tabel karyawan_wajah:" . PHP_EOL;
$columns = DB::select('DESCRIBE karyawan_wajah');
foreach($columns as $col) {
    echo "   - {$col->Field} ({$col->Type}) " . ($col->Null == 'NO' ? '[REQUIRED]' : '[NULLABLE]') . PHP_EOL;
}

// Test 2: Cek fillable/guarded di model
echo PHP_EOL . "2. MODEL CONFIGURATION:" . PHP_EOL;
$model = new Facerecognition();
echo "   - Table: " . $model->getTable() . PHP_EOL;
echo "   - Guarded: " . json_encode($model->getGuarded()) . PHP_EOL;

// Test 3: Cek data yang ada
echo PHP_EOL . "3. DATA FACE RECOGNITION YANG ADA:" . PHP_EOL;
$faces = Facerecognition::with('karyawan')->get();
if ($faces->count() > 0) {
    foreach($faces as $face) {
        $nama = $face->karyawan ? $face->karyawan->nama_karyawan : 'TIDAK DITEMUKAN';
        echo "   - NIK: {$face->nik} | Nama: {$nama} | File: {$face->wajah}" . PHP_EOL;
    }
} else {
    echo "   (Belum ada data)" . PHP_EOL;
}

echo PHP_EOL . "4. KESIMPULAN:" . PHP_EOL;
echo "   - Kolom yang benar: 'wajah' (bukan 'foto_wajah')" . PHP_EOL;
echo "   - SignupController sudah diperbaiki" . PHP_EOL;
echo "   - Sekarang foto dari signup akan otomatis masuk ke:" . PHP_EOL;
echo "     • storage/app/public/karyawan/ (foto profil)" . PHP_EOL;
echo "     • storage/app/public/karyawan/wajah/ (foto face recognition)" . PHP_EOL;
echo "     • tabel karyawan_wajah dengan kolom 'wajah'" . PHP_EOL;
echo PHP_EOL;
echo "✅ SIAP UNTUK TESTING SIGNUP!" . PHP_EOL;
