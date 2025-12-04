<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;

echo "Testing insert transaksi...\n\n";

try {
    // Insert transaksi masuk
    $transaksi = RealisasiDanaOperasional::create([
        'tanggal_realisasi' => '2025-01-13',
        'keterangan' => 'Test Dana Masuk',
        'uraian' => 'Test Dana Masuk',
        'tipe_transaksi' => 'masuk',
        'nominal' => 100000,
        'created_by' => 1,
    ]);
    
    echo "✓ Transaksi berhasil dibuat!\n";
    echo "  Nomor Transaksi: " . $transaksi->nomor_transaksi . "\n";
    echo "  Tanggal: " . $transaksi->tanggal_realisasi . "\n";
    echo "  Keterangan: " . $transaksi->keterangan . "\n";
    echo "  Tipe: " . $transaksi->tipe_transaksi . "\n";
    echo "  Nominal: Rp " . number_format($transaksi->nominal, 0, ',', '.') . "\n\n";
    
    // Cek saldo harian
    $saldo = SaldoHarianOperasional::where('tanggal', '2025-01-13')->first();
    if ($saldo) {
        echo "✓ Saldo harian sudah ter-update:\n";
        echo "  Tanggal: " . $saldo->tanggal . "\n";
        echo "  Saldo Awal: Rp " . number_format($saldo->saldo_awal, 0, ',', '.') . "\n";
        echo "  Dana Masuk: Rp " . number_format($saldo->dana_masuk, 0, ',', '.') . "\n";
        echo "  Dana Keluar: Rp " . number_format($saldo->dana_keluar, 0, ',', '.') . "\n";
        echo "  Saldo Akhir: Rp " . number_format($saldo->saldo_akhir, 0, ',', '.') . "\n";
    } else {
        echo "⚠ Saldo harian belum dibuat (cek observer/event)\n";
    }
    
    // Total data
    echo "\n";
    echo "Total Realisasi: " . RealisasiDanaOperasional::count() . "\n";
    echo "Total Saldo Harian: " . SaldoHarianOperasional::count() . "\n";
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
