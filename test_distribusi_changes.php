<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DistribusiHadiahYayasanMasar;
use App\Models\JamaahMasar;

echo "=== CEK PERUBAHAN RELATIONSHIP ===\n\n";

// Test 1: Check model relationship
$distribusi = DistribusiHadiahYayasanMasar::first();

if ($distribusi) {
    echo "Distribusi ID: {$distribusi->id}\n";
    echo "Jamaah ID di DB: {$distribusi->jamaah_id}\n";
    
    // Try to load jamaah
    if ($distribusi->jamaah) {
        echo "Jamaah yang di-load: {$distribusi->jamaah->nama_jamaah}\n";
        echo "Status: BERHASIL ✓\n";
    } else {
        echo "Jamaah: NULL\n";
        echo "Status: GAGAL - Tidak bisa load jamaah\n";
    }
} else {
    echo "Tidak ada data distribusi di database\n";
}

echo "\n=== VERIFIKASI DATA JAMAAH_MASAR ===\n";
$jamaah = JamaahMasar::where('status_aktif', 'aktif')->first();
if ($jamaah) {
    echo "Jamaah ID: {$jamaah->id}\n";
    echo "Nama: {$jamaah->nama_jamaah}\n";
    echo "Status: AKTIF\n";
} else {
    echo "Tidak ada jamaah aktif\n";
}

echo "\n=== DONE ===\n";
