<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kendaraan;
use Illuminate\Support\Facades\Crypt;

echo "=== TEST EXACT LOAD KENDARAAN ===\n\n";

try {
    // Get kendaraan pertama
    $kendaraan = Kendaraan::first();
    
    if (!$kendaraan) {
        die("❌ Tidak ada data kendaraan\n");
    }
    
    echo "✅ Kendaraan ditemukan: {$kendaraan->kode_kendaraan}\n\n";
    echo "ID untuk URL: " . Crypt::encrypt($kendaraan->id) . "\n\n";
    
    // Simulate exact same eager load dari controller
    echo "--- Loading dengan eager load seperti controller ---\n";
    
    $kendaraan = Kendaraan::with([
        'cabang', 
        'aktivitas' => function($q) { $q->latest()->limit(10); },
        'peminjaman' => function($q) { $q->latest()->limit(10); },
        'services' => function($q) { $q->latest()->limit(10); },
        'jadwalServices',
        'aktivitasAktif',
        'peminjamanAktif',
        'serviceAktif'
    ])->find($kendaraan->id);
    
    echo "✅ Eager load berhasil\n\n";
    
    // Test akses setiap collection
    echo "--- Test Aktivitas Collection ---\n";
    echo "Jumlah aktivitas: " . $kendaraan->aktivitas->count() . "\n";
    
    foreach ($kendaraan->aktivitas as $index => $a) {
        echo "\nAktivitas #{$index}:\n";
        echo "  Type: " . gettype($a) . "\n";
        echo "  Kode: " . $a->kode_aktivitas . "\n";
        echo "  waktu_kembali (raw): ";
        var_dump($a->getAttributes()['waktu_kembali'] ?? 'NULL');
        
        try {
            echo "  waktu_kembali (accessor): ";
            $wk = $a->waktu_kembali;
            if (is_null($wk)) {
                echo "NULL\n";
            } elseif (is_array($wk)) {
                echo "❌ ARRAY DETECTED! => " . json_encode($wk) . "\n";
            } else {
                echo "✅ " . $wk . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
        }
    }
    
    // Test Peminjaman Collection
    echo "\n\n--- Test Peminjaman Collection ---\n";
    echo "Jumlah peminjaman: " . $kendaraan->peminjaman->count() . "\n";
    
    foreach ($kendaraan->peminjaman as $index => $p) {
        echo "\nPeminjaman #{$index}:\n";
        echo "  Type: " . gettype($p) . "\n";
        echo "  Kode: " . $p->kode_peminjaman . "\n";
        echo "  waktu_kembali (raw): ";
        var_dump($p->getAttributes()['waktu_kembali'] ?? 'NULL');
        
        try {
            echo "  waktu_kembali (accessor): ";
            $wk = $p->waktu_kembali;
            if (is_null($wk)) {
                echo "NULL\n";
            } elseif (is_array($wk)) {
                echo "❌ ARRAY DETECTED! => " . json_encode($wk) . "\n";
            } else {
                echo "✅ " . $wk . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
        }
    }
    
    // Test relasi aktif
    echo "\n\n--- Test Relasi Aktif ---\n";
    
    if ($kendaraan->aktivitasAktif) {
        echo "✅ aktivitasAktif exists\n";
        echo "  waktu_kembali: " . ($kendaraan->aktivitasAktif->waktu_kembali ?? 'NULL') . "\n";
    } else {
        echo "aktivitasAktif: NULL\n";
    }
    
    if ($kendaraan->peminjamanAktif) {
        echo "✅ peminjamanAktif exists\n";
        echo "  waktu_kembali: " . ($kendaraan->peminjamanAktif->waktu_kembali ?? 'NULL') . "\n";
    } else {
        echo "peminjamanAktif: NULL\n";
    }
    
    echo "\n\n✅ SEMUA TEST SELESAI - Tidak ada error!\n";
    
} catch (\Exception $e) {
    echo "\n❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
