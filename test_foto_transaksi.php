<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use Illuminate\Support\Facades\Storage;

echo "=== TEST: Tambah Transaksi dengan Foto Dummy ===\n\n";

try {
    // Buat folder jika belum ada
    if (!Storage::disk('public')->exists('bukti-transaksi')) {
        Storage::disk('public')->makeDirectory('bukti-transaksi');
        echo "✓ Folder bukti-transaksi dibuat\n";
    }
    
    // Buat file dummy image
    $dummyImagePath = 'bukti-transaksi/dummy-receipt.txt';
    Storage::disk('public')->put($dummyImagePath, 'DUMMY RECEIPT IMAGE - BBM Rp 500.000');
    echo "✓ File dummy dibuat: $dummyImagePath\n\n";
    
    // Update transaksi yang sudah ada dengan foto
    $transaksi = RealisasiDanaOperasional::where('nomor_transaksi', 'BS-20251113-002')->first();
    
    if ($transaksi) {
        $transaksi->foto_bukti = $dummyImagePath;
        $transaksi->save();
        
        echo "✓ Transaksi updated dengan foto:\n";
        echo "  ID: {$transaksi->id}\n";
        echo "  Nomor: {$transaksi->nomor_transaksi}\n";
        echo "  Keterangan: {$transaksi->keterangan}\n";
        echo "  Foto: {$transaksi->foto_bukti}\n\n";
        
        echo "✓ Sekarang tombol foto akan aktif untuk transaksi ini!\n";
        echo "✓ Refresh halaman dan klik ikon foto untuk melihat.\n";
    } else {
        echo "✗ Transaksi tidak ditemukan\n";
    }
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
}
