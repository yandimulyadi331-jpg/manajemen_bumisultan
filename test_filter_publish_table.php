<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║          TEST FILTER PUBLISH TABLE (DANA OPERASIONAL ONLY)     ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// BEFORE: Semua laporan
echo "📊 SEMUA LAPORAN DI DATABASE:\n";
echo str_repeat("-", 70) . "\n";

$allLaporans = DB::table('laporan_keuangan')
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($allLaporans as $l) {
    echo "ID {$l->id}: {$l->nama_laporan} ({$l->jenis_laporan})\n";
}

echo "\nTotal: " . $allLaporans->count() . " laporan\n\n";

// AFTER: Yang ditampilkan di publish table (Dana Ops only)
echo "✅ YANG DITAMPILKAN DI PUBLISH TABLE (DANA OPERASIONAL):\n";
echo str_repeat("-", 70) . "\n";

$filteredLaporans = DB::table('laporan_keuangan')
    ->whereIn('jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
    ->orderBy('created_at', 'desc')
    ->get();

if ($filteredLaporans->isEmpty()) {
    echo "❌ TIDAK ADA LAPORAN DANA OPERASIONAL\n\n";
    echo "💡 CARA MEMBUAT:\n";
    echo "   1. Buka http://localhost:8000/dana-operasional\n";
    echo "   2. Pilih filter (contoh: Januari 2025)\n";
    echo "   3. Klik tombol 'Download PDF'\n";
    echo "   4. Refresh halaman Laporan Keuangan\n";
} else {
    foreach ($filteredLaporans as $l) {
        $statusIcon = $l->is_published ? '✅' : '📝';
        $statusText = $l->is_published ? 'PUBLISHED' : 'DRAFT';
        
        echo "{$statusIcon} ID {$l->id}: {$l->nama_laporan} ({$l->jenis_laporan}) - {$statusText}\n";
    }
    echo "\nTotal: " . $filteredLaporans->count() . " laporan Dana Operasional\n";
}

echo "\n";

// Yang TIDAK ditampilkan (Annual Report)
echo "❌ YANG TIDAK DITAMPILKAN (ANNUAL REPORT):\n";
echo str_repeat("-", 70) . "\n";

$annualReports = DB::table('laporan_keuangan')
    ->where('jenis_laporan', 'LAPORAN_BUDGET')
    ->get();

if ($annualReports->isEmpty()) {
    echo "Tidak ada Annual Report\n";
} else {
    foreach ($annualReports as $l) {
        echo "ID {$l->id}: {$l->nama_laporan} ({$l->jenis_laporan})\n";
    }
    echo "\nTotal: " . $annualReports->count() . " Annual Report (disembunyikan dari publish table)\n";
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                         KESIMPULAN                             ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

if ($filteredLaporans->isEmpty()) {
    echo "❗ STATUS: Tabel publish akan KOSONG\n";
    echo "📋 ACTION: Download PDF dari Dana Operasional dulu\n";
} else {
    echo "✅ STATUS: Tabel publish akan menampilkan {$filteredLaporans->count()} laporan\n";
    echo "📋 ACTION: Bisa langsung publish ke karyawan\n";
}

if (!$annualReports->isEmpty()) {
    echo "ℹ️  INFO: {$annualReports->count()} Annual Report tidak akan muncul di tabel publish\n";
}

echo "\n";
