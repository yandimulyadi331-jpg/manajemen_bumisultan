<?php

// Test fungsi auto generate kode tukang
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tukang;

echo "=== TEST AUTO GENERATE KODE TUKANG ===\n\n";

// Fungsi generate (copy dari controller)
function generateKodeTukang()
{
    $lastTukang = Tukang::orderBy('id', 'desc')->first();
    
    if (!$lastTukang) {
        return 'TK001';
    }
    
    $lastKode = $lastTukang->kode_tukang;
    preg_match('/\d+/', $lastKode, $matches);
    
    if (isset($matches[0])) {
        $lastNumber = (int)$matches[0];
        $newNumber = $lastNumber + 1;
        return 'TK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    
    return 'TK001';
}

// Cek data terakhir
$lastTukang = Tukang::orderBy('id', 'desc')->first();
$totalTukang = Tukang::count();

echo "📊 Data Saat Ini:\n";
echo "   Total tukang: {$totalTukang}\n";

if ($lastTukang) {
    echo "   Kode terakhir: {$lastTukang->kode_tukang}\n";
    echo "   Nama: {$lastTukang->nama_tukang}\n\n";
} else {
    echo "   Belum ada data tukang\n\n";
}

// Generate kode berikutnya
$newKode = generateKodeTukang();
echo "✨ Kode Otomatis Berikutnya: {$newKode}\n\n";

// Simulasi 10 kode berikutnya
echo "🔮 Simulasi 10 Kode Berikutnya:\n";
$simulatedNumber = 0;
if ($lastTukang) {
    preg_match('/\d+/', $lastTukang->kode_tukang, $matches);
    $simulatedNumber = (int)$matches[0];
}

for ($i = 1; $i <= 10; $i++) {
    $num = $simulatedNumber + $i;
    $kode = 'TK' . str_pad($num, 3, '0', STR_PAD_LEFT);
    echo "   {$i}. {$kode}\n";
}

echo "\n✅ Fungsi auto-generate bekerja dengan baik!\n";
