<?php
// Test fix datetime format

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PresensiYayasan;

echo "=== TEST: Insert dengan jam_in/jam_out format ===\n\n";

try {
    // 1. Test dengan format DATETIME lengkap
    echo "1️⃣  Test insert format DATETIME lengkap:\n";
    
    $data1 = [
        'kode_yayasan' => '251200002',
        'tanggal' => '2025-12-03',
        'jam_in' => '2025-12-03 14:30:00',
        'jam_out' => null,
        'kode_jam_kerja' => 'JK01',
        'status' => 'h'
    ];
    
    try {
        $presensi1 = PresensiYayasan::create($data1);
        echo "   ✅ INSERT BERHASIL\n";
        echo "      - jam_in: {$presensi1->jam_in}\n";
        echo "      - jam_out: {$presensi1->jam_out}\n";
    } catch (\Exception $e) {
        echo "   ❌ ERROR: " . $e->getMessage() . "\n";
    }
    
    // 2. Test dengan format TIME saja (seharusnya di-format di controller)
    echo "\n2️⃣  Simulasi form submit dengan TIME saja:\n";
    
    // Simulasi apa yang dilakukan controller
    $tanggal = '2025-12-03';
    $jam_in_from_form = '14:45:00';  // hanya TIME
    
    // Format di controller
    $jam_in_formatted = null;
    if ($jam_in_from_form) {
        if (strpos($jam_in_from_form, ' ') !== false) {
            $jam_in_formatted = $jam_in_from_form;
        } else {
            $jam_in_formatted = $tanggal . ' ' . $jam_in_from_form;
        }
    }
    
    $data2 = [
        'kode_yayasan' => '251200003',
        'tanggal' => $tanggal,
        'jam_in' => $jam_in_formatted,
        'jam_out' => null,
        'kode_jam_kerja' => 'JK01',
        'status' => 'h'
    ];
    
    try {
        $presensi2 = PresensiYayasan::create($data2);
        echo "   ✅ INSERT BERHASIL\n";
        echo "      - Input form: {$jam_in_from_form}\n";
        echo "      - Di-format menjadi: {$jam_in_formatted}\n";
        echo "      - Database jam_in: {$presensi2->jam_in}\n";
    } catch (\Exception $e) {
        echo "   ❌ ERROR: " . $e->getMessage() . "\n";
    }
    
    echo "\n✅ TEST COMPLETE\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
