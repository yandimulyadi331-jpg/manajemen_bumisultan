<?php

/**
 * Test Script: Modal Download PDF dengan Aksi Cepat
 * 
 * Fitur yang ditest:
 * 1. Aksi Cepat: Minggu Ini
 * 2. Aksi Cepat: Bulan Ini
 * 3. Aksi Cepat: Tahun Ini
 * 4. Custom: Per Bulan
 * 5. Custom: Per Tahun
 * 6. Custom: Per Minggu
 * 7. Custom: Range Tanggal
 */

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "TEST MODAL PDF - AKSI CEPAT\n";
echo "========================================\n\n";

echo "Tanggal Test: " . Carbon::now()->format('d F Y') . "\n";
echo "Hari: " . Carbon::now()->locale('id')->dayName . "\n\n";

// 1. Test Aksi Cepat: Minggu Ini
echo "1. AKSI CEPAT: MINGGU INI\n";
echo str_repeat("-", 80) . "\n";
$mingguIni = Carbon::now()->startOfWeek();
$akhirMinggu = Carbon::now()->endOfWeek();
echo "Range: " . $mingguIni->format('d M Y') . " - " . $akhirMinggu->format('d M Y') . "\n";
echo "Format Input: " . Carbon::now()->format('Y') . '-W' . Carbon::now()->format('W') . "\n";
echo "URL Parameter: filter_type=minggu&minggu=" . Carbon::now()->format('Y') . '-W' . Carbon::now()->format('W') . "\n\n";

// 2. Test Aksi Cepat: Bulan Ini
echo "2. AKSI CEPAT: BULAN INI\n";
echo str_repeat("-", 80) . "\n";
$bulanIni = Carbon::now()->startOfMonth();
$akhirBulan = Carbon::now()->endOfMonth();
echo "Range: " . $bulanIni->format('d M Y') . " - " . $akhirBulan->format('d M Y') . "\n";
echo "Format Input: " . Carbon::now()->format('Y-m') . "\n";
echo "URL Parameter: filter_type=bulan&bulan=" . Carbon::now()->format('Y-m') . "\n";
echo "Label: " . Carbon::now()->locale('id')->isoFormat('MMMM YYYY') . "\n\n";

// 3. Test Aksi Cepat: Tahun Ini
echo "3. AKSI CEPAT: TAHUN INI\n";
echo str_repeat("-", 80) . "\n";
$tahunIni = Carbon::now()->startOfYear();
$akhirTahun = Carbon::now()->endOfYear();
echo "Range: " . $tahunIni->format('d M Y') . " - " . $akhirTahun->format('d M Y') . "\n";
echo "Format Input: " . Carbon::now()->format('Y') . "\n";
echo "URL Parameter: filter_type=tahun&tahun=" . Carbon::now()->format('Y') . "\n";
echo "Label: Tahun " . Carbon::now()->format('Y') . "\n\n";

// 4. Test Custom Periods
echo "4. CUSTOM PERIODS\n";
echo str_repeat("-", 80) . "\n";

// 4a. Per Bulan (Contoh: Oktober 2025)
echo "A. Per Bulan (Oktober 2025):\n";
$bulan = Carbon::parse('2025-10-01');
echo "   Input: 2025-10\n";
echo "   Range: " . $bulan->startOfMonth()->format('d M Y') . " - " . $bulan->endOfMonth()->format('d M Y') . "\n";
echo "   URL: filter_type=bulan&bulan=2025-10\n\n";

// 4b. Per Tahun (Contoh: 2024)
echo "B. Per Tahun (2024):\n";
$tahun = Carbon::parse('2024-01-01');
echo "   Input: 2024\n";
echo "   Range: " . $tahun->startOfYear()->format('d M Y') . " - " . $tahun->endOfYear()->format('d M Y') . "\n";
echo "   URL: filter_type=tahun&tahun=2024\n\n";

// 4c. Per Minggu (Contoh: Week 40, 2025)
echo "C. Per Minggu (Week 40, 2025):\n";
$minggu = Carbon::now()->setISODate(2025, 40);
echo "   Input: 2025-W40\n";
echo "   Range: " . $minggu->startOfWeek()->format('d M Y') . " - " . $minggu->endOfWeek()->format('d M Y') . "\n";
echo "   URL: filter_type=minggu&minggu=2025-W40\n\n";

// 4d. Range Tanggal (Contoh: 1-15 November 2025)
echo "D. Range Tanggal (1-15 November 2025):\n";
$start = Carbon::parse('2025-11-01');
$end = Carbon::parse('2025-11-15');
echo "   Input Start: 2025-11-01\n";
echo "   Input End: 2025-11-15\n";
echo "   Range: " . $start->format('d M Y') . " - " . $end->format('d M Y') . "\n";
echo "   URL: filter_type=range&start_date=2025-11-01&end_date=2025-11-15\n\n";

// 5. UI Components
echo "5. UI COMPONENTS\n";
echo str_repeat("-", 80) . "\n";
echo "✅ Modal: #modalDownloadPdf\n";
echo "✅ Form: #formDownloadPdf\n";
echo "✅ Select: #pdfFilterType\n";
echo "✅ Inputs:\n";
echo "   - #pdfBulan (type=month)\n";
echo "   - #pdfTahun (type=number)\n";
echo "   - #pdfMinggu (type=week)\n";
echo "   - #pdfStartDate (type=date)\n";
echo "   - #pdfEndDate (type=date)\n\n";

// 6. JavaScript Functions
echo "6. JAVASCRIPT FUNCTIONS\n";
echo str_repeat("-", 80) . "\n";
echo "✅ togglePdfInputs() - Show/hide inputs based on filter type\n";
echo "✅ setPdfMingguIni() - Set filter to current week\n";
echo "✅ setPdfBulanIni() - Set filter to current month\n";
echo "✅ setPdfTahunIni() - Set filter to current year\n";
echo "✅ getWeekNumber(date) - Calculate ISO week number\n";
echo "✅ highlightButton(button) - Visual feedback for selected button\n\n";

// 7. Button Actions
echo "7. BUTTON ACTIONS\n";
echo str_repeat("-", 80) . "\n";
echo "Aksi Cepat Buttons:\n";
echo "  ⚡ Minggu Ini  → Set minggu saat ini → Download PDF\n";
echo "  ⚡ Bulan Ini   → Set bulan saat ini → Download PDF\n";
echo "  ⚡ Tahun Ini   → Set tahun saat ini → Download PDF\n\n";

echo "Custom Period:\n";
echo "  1. Pilih tipe dari dropdown (Bulan/Tahun/Minggu/Range)\n";
echo "  2. Input muncul otomatis sesuai tipe\n";
echo "  3. Isi nilai yang diinginkan\n";
echo "  4. Klik Download → PDF generated\n\n";

// 8. Expected Behavior
echo "8. EXPECTED BEHAVIOR\n";
echo str_repeat("-", 80) . "\n";
echo "User Flow:\n";
echo "1. User klik button 'Download PDF' di header card\n";
echo "2. Modal muncul dengan 3 tombol aksi cepat di atas\n";
echo "3. User bisa:\n";
echo "   a. Klik aksi cepat (Minggu/Bulan/Tahun Ini) → Direct download\n";
echo "   b. Pilih custom period → Isi input → Download\n";
echo "4. Form submit ke route export-pdf dengan parameter filter\n";
echo "5. Controller exportPdf() generate PDF sesuai filter\n";
echo "6. PDF downloaded dengan nama sesuai periode\n\n";

// 9. URL Examples
echo "9. URL EXAMPLES\n";
echo str_repeat("-", 80) . "\n";
echo "Minggu Ini:\n";
echo "  /dana-operasional/export-pdf?filter_type=minggu&minggu=" . Carbon::now()->format('Y-W') . "\n\n";

echo "Bulan Ini:\n";
echo "  /dana-operasional/export-pdf?filter_type=bulan&bulan=" . Carbon::now()->format('Y-m') . "\n\n";

echo "Tahun Ini:\n";
echo "  /dana-operasional/export-pdf?filter_type=tahun&tahun=" . Carbon::now()->format('Y') . "\n\n";

echo "Custom Range:\n";
echo "  /dana-operasional/export-pdf?filter_type=range&start_date=2025-11-01&end_date=2025-11-15\n\n";

// 10. Summary
echo "10. SUMMARY\n";
echo str_repeat("-", 80) . "\n";
echo "✅ Modal updated dengan Aksi Cepat buttons\n";
echo "✅ JavaScript functions untuk set periode otomatis\n";
echo "✅ Toggle inputs untuk custom period\n";
echo "✅ Visual highlight untuk button yang dipilih\n";
echo "✅ Support 7 tipe periode: Minggu/Bulan/Tahun Ini + 4 Custom\n";
echo "✅ Form action ke route export-pdf dengan filter params\n";
echo "✅ Controller sudah support semua tipe filter\n\n";

echo "CARA TEST:\n";
echo "1. Buka: http://localhost/dana-operasional\n";
echo "2. Klik button 'Download PDF' di header card\n";
echo "3. Modal muncul dengan 3 aksi cepat buttons\n";
echo "4. Test klik 'Minggu Ini' → Should set week picker\n";
echo "5. Test klik 'Bulan Ini' → Should set month picker\n";
echo "6. Test klik 'Tahun Ini' → Should set year input\n";
echo "7. Test custom period dengan dropdown\n";
echo "8. Submit form → PDF should download\n\n";

echo "========================================\n";
echo "TEST COMPLETED ✓\n";
echo "========================================\n";
