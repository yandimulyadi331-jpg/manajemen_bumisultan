<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pinjaman;
use Illuminate\Support\Facades\Mail;
use App\Mail\PinjamanJatuhTempoMail;

echo "\n";
echo "========================================\n";
echo "   TEST KIRIM EMAIL KE PINJAMAN REAL\n";
echo "========================================\n\n";

// Cari pinjaman dengan email
$pinjaman = Pinjaman::with(['karyawan'])
    ->whereNotNull('email_peminjam')
    ->first();

if (!$pinjaman) {
    echo "❌ Tidak ada pinjaman dengan email_peminjam!\n";
    echo "Coba cari crew dengan email...\n\n";
    
    // Coba cari karyawan yang punya email (jika kolom email ada)
    $pinjaman = Pinjaman::with(['karyawan'])
        ->where('kategori_peminjam', 'crew')
        ->first();
    
    if (!$pinjaman) {
        echo "❌ Tidak ada pinjaman sama sekali!\n";
        exit;
    }
}

// Tentukan email tujuan
$emailTujuan = null;
if ($pinjaman->kategori_peminjam === 'non_crew' && $pinjaman->email_peminjam) {
    $emailTujuan = $pinjaman->email_peminjam;
} else {
    // Gunakan email testing
    $emailTujuan = 'manajemenbumisultan@gmail.com';
    echo "ℹ️  Menggunakan email testing karena pinjaman tidak punya email\n\n";
}

if (!$emailTujuan) {
    echo "❌ Email tidak tersedia!\n";
    exit;
}

echo "📧 DETAIL PINJAMAN:\n";
echo "  Nomor: {$pinjaman->nomor_pinjaman}\n";
echo "  Nama: {$pinjaman->nama_peminjam}\n";
echo "  Email: {$emailTujuan}\n";
echo "  Kategori: " . strtoupper($pinjaman->kategori_peminjam) . "\n\n";

echo "📤 Mengirim email...\n";

try {
    Mail::to($emailTujuan)->send(
        new PinjamanJatuhTempoMail($pinjaman, 'jatuh_tempo_hari_ini', 0)
    );
    
    echo "✅ BERHASIL! Email terkirim ke: {$emailTujuan}\n";
    
} catch (\Exception $e) {
    echo "❌ GAGAL!\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "Cek inbox: {$emailTujuan}\n";
echo "========================================\n\n";
