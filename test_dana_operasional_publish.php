<?php

/**
 * Script untuk test Dana Operasional integration dengan publish system
 * 
 * Cara pakai:
 * 1. Download PDF dari Dana Operasional (klik tombol Download PDF)
 * 2. Jalankan script ini: php test_dana_operasional_publish.php
 * 3. Cek hasilnya di tabel laporan_keuangan
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK DATA DANA OPERASIONAL DI PUBLISH TABLE ===\n\n";

// Ambil semua laporan Dana Operasional (bukan Annual Report)
$laporanDanaOps = DB::table('laporan_keuangan')
    ->whereIn('jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
    ->orderBy('created_at', 'desc')
    ->get();

if ($laporanDanaOps->isEmpty()) {
    echo "❌ Belum ada laporan Dana Operasional yang tersimpan.\n";
    echo "📝 Silakan download PDF dari menu Dana Operasional terlebih dahulu.\n\n";
    exit;
}

echo "✅ Ditemukan " . $laporanDanaOps->count() . " laporan Dana Operasional\n\n";

foreach ($laporanDanaOps as $laporan) {
    echo "-----------------------------------\n";
    echo "ID: {$laporan->id}\n";
    echo "Nomor: {$laporan->nomor_laporan}\n";
    echo "Jenis: {$laporan->jenis_laporan}\n";
    echo "Nama: {$laporan->nama_laporan}\n";
    echo "Periode: {$laporan->periode}\n";
    echo "Tanggal: {$laporan->tanggal_mulai} s/d {$laporan->tanggal_selesai}\n";
    echo "Status: {$laporan->status}\n";
    echo "Published: " . ($laporan->is_published ? '✅ YA' : '❌ TIDAK') . "\n";
    
    if ($laporan->is_published) {
        echo "Published At: {$laporan->published_at}\n";
        echo "Published By: {$laporan->published_by}\n";
    }
    
    echo "File PDF: {$laporan->file_pdf}\n";
    
    // Cek apakah file exists
    $filePath = storage_path('app/public/' . $laporan->file_pdf);
    if (file_exists($filePath)) {
        $fileSize = round(filesize($filePath) / 1024, 2);
        echo "File Status: ✅ EXISTS ({$fileSize} KB)\n";
    } else {
        echo "File Status: ❌ NOT FOUND\n";
    }
    
    echo "Created: {$laporan->created_at}\n";
    echo "\n";
}

echo "-----------------------------------\n\n";

// Cek laporan yang sudah dipublish
$publishedCount = $laporanDanaOps->where('is_published', true)->count();
$draftCount = $laporanDanaOps->where('is_published', false)->count();

echo "RINGKASAN:\n";
echo "✅ Published: {$publishedCount}\n";
echo "📝 Draft: {$draftCount}\n";
echo "📊 Total: " . $laporanDanaOps->count() . "\n\n";

echo "💡 NEXT STEPS:\n";
echo "1. Buka /laporan-keuangan untuk publish laporan\n";
echo "2. Klik tombol 'Publish' pada laporan yang ingin dipublish\n";
echo "3. Login sebagai karyawan dan buka menu 'Laporan'\n";
echo "4. Laporan yang sudah dipublish akan muncul di sana\n\n";
