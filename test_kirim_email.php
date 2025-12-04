<?php

/**
 * TEST: Kirim Email Manual
 * Script untuk test kirim email langsung ke email tertentu
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "\n";
echo "========================================\n";
echo "   TEST KIRIM EMAIL MANUAL             \n";
echo "========================================\n\n";

// Konfigurasi
$emailTujuan = 'manajemenbumisultan@gmail.com'; // Ganti dengan email Anda untuk test
$namaSubject = 'Test Email dari Laravel';
$isiPesan = 'Ini adalah test email dari sistem notifikasi pinjaman Bumi Sultan.';

echo "📧 Mengirim test email...\n";
echo "   Tujuan: {$emailTujuan}\n";
echo "   Subject: {$namaSubject}\n\n";

try {
    Mail::raw($isiPesan, function($message) use ($emailTujuan, $namaSubject) {
        $message->to($emailTujuan)
                ->subject($namaSubject);
    });
    
    echo "✅ EMAIL BERHASIL DIKIRIM!\n\n";
    echo "Cek inbox email Anda: {$emailTujuan}\n";
    echo "Jika tidak ada di Inbox, cek folder SPAM/Junk\n\n";
    
} catch (\Exception $e) {
    echo "❌ GAGAL KIRIM EMAIL!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Kemungkinan penyebab:\n";
    echo "1. Konfigurasi SMTP di .env salah\n";
    echo "2. App Password tidak valid\n";
    echo "3. Koneksi internet bermasalah\n";
    echo "4. Gmail memblokir akses\n\n";
}

echo "========================================\n\n";

echo "💡 TIPS:\n";
echo "- Jika berhasil, cek inbox email Anda\n";
echo "- Jika tidak ada, cek folder SPAM\n";
echo "- Jika gagal, cek file .env lagi\n\n";
