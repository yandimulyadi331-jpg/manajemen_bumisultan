<?php
// Test insert dengan data kode_yayasan

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DistribusiHadiah;
use Illuminate\Support\Facades\DB;

echo "=== TEST: Insert distribusi_hadiah dengan kode_yayasan ===\n\n";

try {
    // 1. Test dengan jamaah_id = kode_yayasan (251200004 = DANI)
    echo "1️⃣  Test insert dengan jamaah_id='251200004' (DANI):\n";
    
    $testData = [
        'nomor_distribusi' => 'DST-' . date('Ymd') . '-TEST',
        'jamaah_id' => '251200004',  // kode_yayasan DANI
        'hadiah_id' => 4,
        'tanggal_distribusi' => now(),
        'jumlah' => 1,
        'ukuran' => null,
        'penerima' => 'Test Insert DANI',
        'petugas_distribusi' => 'Test',
        'keterangan' => 'Test insert dengan kode_yayasan',
        'status_distribusi' => 'diterima'
    ];
    
    $distribusi = DistribusiHadiah::create($testData);
    echo "   ✅ INSERT BERHASIL\n";
    echo "      - ID: {$distribusi->id}\n";
    echo "      - jamaah_id: {$distribusi->jamaah_id}\n";
    echo "      - nomor_distribusi: {$distribusi->nomor_distribusi}\n";
    echo "      - penerima: {$distribusi->penerima}\n";

    // 2. Test dengan jamaah_id = NULL (Non-jamaah)
    echo "\n2️⃣  Test insert dengan jamaah_id=NULL (Non-Jamaah):\n";
    
    $testData2 = [
        'nomor_distribusi' => 'DST-' . date('Ymd') . '-TEST2',
        'jamaah_id' => null,  // NULL = Non-jamaah
        'hadiah_id' => 4,
        'tanggal_distribusi' => now(),
        'jumlah' => 1,
        'ukuran' => null,
        'penerima' => 'Test Non-Jamaah',
        'petugas_distribusi' => 'Test',
        'keterangan' => 'Test insert non-jamaah',
        'status_distribusi' => 'diterima'
    ];
    
    $distribusi2 = DistribusiHadiah::create($testData2);
    echo "   ✅ INSERT BERHASIL\n";
    echo "      - ID: {$distribusi2->id}\n";
    echo "      - jamaah_id: {$distribusi2->jamaah_id} (NULL)\n";
    echo "      - nomor_distribusi: {$distribusi2->nomor_distribusi}\n";
    echo "      - penerima: {$distribusi2->penerima}\n";

    // 3. Verify dengan raw query
    echo "\n3️⃣  Verify data di database:\n";
    $verified = DB::select("SELECT id, jamaah_id, penerima, status_distribusi FROM distribusi_hadiah 
                            WHERE nomor_distribusi LIKE 'DST-%TEST%'
                            ORDER BY id DESC");
    
    foreach ($verified as $row) {
        echo "   - ID: {$row->id} | jamaah_id: {$row->jamaah_id} | penerima: {$row->penerima}\n";
    }

    echo "\n✅ SEMUA TEST PASSED - FK CONSTRAINT BEKERJA!\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Code: " . $e->getCode() . "\n";
}
?>
