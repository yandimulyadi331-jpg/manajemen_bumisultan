<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KpiCrew;
use App\Models\Karyawan;

echo "=== TEST ELOQUENT RELATION ===\n\n";

// Test 1: Ambil KPI tanpa eager loading
$kpi = KpiCrew::where('bulan', 11)->where('tahun', 2025)->first();
echo "Test 1 - Lazy Loading:\n";
echo "KPI NIK: {$kpi->nik}\n";
echo "Accessing karyawan relation...\n";
try {
    if ($kpi->karyawan) {
        echo "✓ Karyawan found: {$kpi->karyawan->nama_karyawan}\n";
    } else {
        echo "✗ Karyawan is NULL\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Eager loading
echo "Test 2 - Eager Loading:\n";
$kpiWithKaryawan = KpiCrew::with('karyawan')
    ->where('bulan', 11)
    ->where('tahun', 2025)
    ->first();
    
echo "KPI NIK: {$kpiWithKaryawan->nik}\n";
if ($kpiWithKaryawan->karyawan) {
    echo "✓ Karyawan found: {$kpiWithKaryawan->karyawan->nama_karyawan}\n";
} else {
    echo "✗ Karyawan is NULL\n";
}

echo "\n";

// Test 3: Direct query dari Karyawan
echo "Test 3 - Direct Karyawan Query:\n";
$kar = Karyawan::where('nik', $kpi->nik)->first();
if ($kar) {
    echo "✓ Karyawan found directly: {$kar->nama_karyawan}\n";
} else {
    echo "✗ Karyawan not found\n";
}

echo "\n";

// Test 4: Check relation query
echo "Test 4 - Relation Query SQL:\n";
$relationQuery = $kpi->karyawan();
echo "Relation SQL: " . $relationQuery->toSql() . "\n";
echo "Bindings: " . json_encode($relationQuery->getBindings()) . "\n";

$relResult = $relationQuery->first();
if ($relResult) {
    echo "✓ Result: {$relResult->nama_karyawan}\n";
} else {
    echo "✗ No result\n";
}

echo "\n=== TEST SEMUA KPI ===\n";
$allKpi = KpiCrew::where('bulan', 11)->where('tahun', 2025)->get();
foreach($allKpi as $k) {
    $karDirect = Karyawan::where('nik', $k->nik)->first();
    $karRelation = $k->karyawan;
    
    echo "NIK: {$k->nik}\n";
    echo "  Direct query: " . ($karDirect ? "✓ " . $karDirect->nama_karyawan : "✗ NULL") . "\n";
    echo "  Relation: " . ($karRelation ? "✓ " . $karRelation->nama_karyawan : "✗ NULL") . "\n";
    echo "\n";
}
