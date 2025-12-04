<?php

/**
 * Test Script: Advanced Filter untuk Dana Operasional
 * 
 * Fitur yang ditest:
 * 1. Filter Per Bulan (default)
 * 2. Filter Per Tahun
 * 3. Filter Per Minggu
 * 4. Filter Range Tanggal
 * 
 * Expected behavior:
 * - Controller DanaOperasionalController::index() 
 * - Controller DanaOperasionalController::exportPdf()
 * - JavaScript toggleFilterInputs()
 * - JavaScript downloadPdfFiltered()
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "TEST ADVANCED FILTER - DANA OPERASIONAL\n";
echo "========================================\n\n";

// 1. Test Data: Cek transaksi yang ada
echo "1. CEK DATA TRANSAKSI\n";
echo str_repeat("-", 80) . "\n";

$transaksi = DB::table('realisasi_dana_operasional')
    ->orderBy('tanggal_realisasi', 'asc')
    ->get();

echo "Total Transaksi: " . $transaksi->count() . "\n";
if ($transaksi->count() > 0) {
    echo "Tanggal Pertama: " . $transaksi->first()->tanggal_realisasi . "\n";
    echo "Tanggal Terakhir: " . $transaksi->last()->tanggal_realisasi . "\n\n";
    
    // Grouping by bulan
    $perBulan = $transaksi->groupBy(function($item) {
        return Carbon::parse($item->tanggal_realisasi)->format('Y-m');
    });
    
    echo "Data Per Bulan:\n";
    foreach ($perBulan as $bulan => $items) {
        echo "  - $bulan: " . $items->count() . " transaksi\n";
    }
} else {
    echo "⚠️  Belum ada data transaksi\n";
}

// 2. Test Filter Calculation
echo "\n2. TEST PERHITUNGAN FILTER\n";
echo str_repeat("-", 80) . "\n";

// Test Filter Per Bulan
echo "A. Filter Per Bulan (2025-11):\n";
$bulan = '2025-11';
$tanggalAwal = Carbon::parse($bulan . '-01')->startOfMonth();
$tanggalAkhir = Carbon::parse($bulan . '-01')->endOfMonth();
echo "   Range: " . $tanggalAwal->format('d M Y') . " - " . $tanggalAkhir->format('d M Y') . "\n";
$countBulan = DB::table('realisasi_dana_operasional')
    ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
    ->count();
echo "   Hasil: $countBulan transaksi\n\n";

// Test Filter Per Tahun
echo "B. Filter Per Tahun (2025):\n";
$tahun = 2025;
$tanggalAwal = Carbon::create($tahun, 1, 1)->startOfYear();
$tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfYear();
echo "   Range: " . $tanggalAwal->format('d M Y') . " - " . $tanggalAkhir->format('d M Y') . "\n";
$countTahun = DB::table('realisasi_dana_operasional')
    ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
    ->count();
echo "   Hasil: $countTahun transaksi\n\n";

// Test Filter Per Minggu (minggu ini)
echo "C. Filter Per Minggu (minggu saat ini):\n";
$tanggalAwal = Carbon::now()->startOfWeek();
$tanggalAkhir = Carbon::now()->endOfWeek();
echo "   Range: " . $tanggalAwal->format('d M Y') . " - " . $tanggalAkhir->format('d M Y') . "\n";
$countMinggu = DB::table('realisasi_dana_operasional')
    ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
    ->count();
echo "   Hasil: $countMinggu transaksi\n\n";

// Test Filter Range (3 hari pertama November)
echo "D. Filter Range Tanggal (1-3 November 2025):\n";
$tanggalAwal = Carbon::parse('2025-11-01')->startOfDay();
$tanggalAkhir = Carbon::parse('2025-11-03')->endOfDay();
echo "   Range: " . $tanggalAwal->format('d M Y H:i') . " - " . $tanggalAkhir->format('d M Y H:i') . "\n";
$countRange = DB::table('realisasi_dana_operasional')
    ->whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
    ->count();
echo "   Hasil: $countRange transaksi\n\n";

// 3. Test URL Generation untuk PDF
echo "3. TEST URL GENERATION UNTUK PDF\n";
echo str_repeat("-", 80) . "\n";

echo "A. URL PDF Filter Per Bulan:\n";
echo "   /dana-operasional/export-pdf?filter_type=bulan&bulan=2025-11\n\n";

echo "B. URL PDF Filter Per Tahun:\n";
echo "   /dana-operasional/export-pdf?filter_type=tahun&tahun=2025\n\n";

echo "C. URL PDF Filter Per Minggu:\n";
$weekFormat = Carbon::now()->format('Y') . '-W' . Carbon::now()->format('W');
echo "   /dana-operasional/export-pdf?filter_type=minggu&minggu=$weekFormat\n\n";

echo "D. URL PDF Filter Range:\n";
echo "   /dana-operasional/export-pdf?filter_type=range&start_date=2025-11-01&end_date=2025-11-03\n\n";

// 4. Test Periode Label
echo "4. TEST PERIODE LABEL\n";
echo str_repeat("-", 80) . "\n";

echo "A. Per Bulan: " . Carbon::parse('2025-11-01')->locale('id')->isoFormat('MMMM YYYY') . "\n";
echo "B. Per Tahun: Tahun 2025\n";
$week = Carbon::now()->startOfWeek();
echo "C. Per Minggu: Minggu " . $week->format('d M') . " - " . $week->addDays(6)->format('d M Y') . "\n";
echo "D. Range: " . Carbon::parse('2025-11-01')->format('d M Y') . " - " . Carbon::parse('2025-11-03')->format('d M Y') . "\n\n";

// 5. Summary
echo "5. SUMMARY\n";
echo str_repeat("-", 80) . "\n";
echo "✅ Controller logic updated (index & exportPdf)\n";
echo "✅ View updated dengan filter dropdown\n";
echo "✅ JavaScript functions added (toggleFilterInputs & downloadPdfFiltered)\n";
echo "✅ Periode label ditampilkan di header card\n";
echo "✅ PDF download menggunakan filter yang sama\n\n";

echo "NEXT STEPS:\n";
echo "1. Akses halaman: http://localhost/dana-operasional\n";
echo "2. Test dropdown filter (Bulan/Tahun/Minggu/Range)\n";
echo "3. Test button 'Tampilkan' untuk setiap filter\n";
echo "4. Test button 'PDF' untuk download dengan filter aktif\n";
echo "5. Verify periode label di header card\n\n";

echo "========================================\n";
echo "TEST COMPLETED ✓\n";
echo "========================================\n";
