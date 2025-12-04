<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KehadiranTukang;
use App\Models\KeuanganTukang;

echo "=== TESTING INTEGRASI KEHADIRAN -> KEUANGAN ===\n\n";

// Ambil kehadiran yang ada
$kehadiran = KehadiranTukang::with('tukang')->first();

if ($kehadiran) {
    echo "Data Kehadiran:\n";
    echo "- Tukang: " . $kehadiran->tukang->nama_tukang . "\n";
    echo "- Tanggal: " . $kehadiran->tanggal . "\n";
    echo "- Status: " . $kehadiran->status . "\n";
    echo "- Lembur: " . $kehadiran->lembur . "\n";
    echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
    echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
    echo "- Total Upah: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . "\n\n";
    
    // Cek transaksi keuangan terkait
    $transaksi = KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)->get();
    
    echo "Transaksi Keuangan Terkait: " . $transaksi->count() . " record\n";
    if ($transaksi->count() > 0) {
        foreach ($transaksi as $t) {
            echo "  - " . $t->jenis_transaksi . ": Rp " . number_format($t->jumlah, 0, ',', '.') . " (" . $t->keterangan . ")\n";
        }
    } else {
        echo "  ⚠️  Belum ada transaksi (perlu sync manual atau toggle di UI)\n";
    }
} else {
    echo "⚠️  Belum ada data kehadiran\n";
}

echo "\n=== SELESAI ===\n";
