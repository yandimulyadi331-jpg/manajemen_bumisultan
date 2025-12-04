<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tukang;
use App\Models\KehadiranTukang;
use App\Models\KeuanganTukang;

echo "=== TEST: TUKANG TIDAK HADIR TAPI LEMBUR ===\n\n";

// Cari tukang wawan
$wawan = Tukang::where('nama_tukang', 'wawan')->first();

if ($wawan) {
    echo "Tukang: " . $wawan->nama_tukang . " (Kode: " . $wawan->kode_tukang . ")\n";
    echo "Tarif Harian: Rp " . number_format($wawan->tarif_harian, 0, ',', '.') . "\n\n";
    
    // Cek kehadiran hari ini
    $tanggal = date('Y-m-d');
    $kehadiran = KehadiranTukang::where('tukang_id', $wawan->id)
                                ->where('tanggal', $tanggal)
                                ->first();
    
    if ($kehadiran) {
        echo "Status Kehadiran Hari Ini:\n";
        echo "- Status: " . $kehadiran->status . "\n";
        echo "- Lembur: " . $kehadiran->lembur . "\n";
        echo "- Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
        echo "- Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
        echo "- Total Upah: Rp " . number_format($kehadiran->total_upah, 0, ',', '.') . "\n\n";
        
        // Cek transaksi keuangan
        $transaksi = KeuanganTukang::where('kehadiran_tukang_id', $kehadiran->id)->get();
        echo "Transaksi Keuangan: " . $transaksi->count() . " record\n";
        foreach ($transaksi as $t) {
            echo "  - " . $t->jenis_transaksi . ": Rp " . number_format($t->jumlah, 0, ',', '.') . "\n";
        }
    } else {
        echo "❌ Belum ada kehadiran hari ini\n";
        echo "💡 Silakan klik toggle Lembur di browser untuk test fitur baru!\n";
    }
} else {
    echo "❌ Tukang wawan tidak ditemukan\n";
}

echo "\n=== SELESAI ===\n";
