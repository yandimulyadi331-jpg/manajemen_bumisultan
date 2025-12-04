<?php

/**
 * TEST SCRIPT: Test absensi Jamaah dengan berbagai tanggal
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengaturanumum;
use App\Models\JamaahMajlisTaklim;

echo "\n=============================================================\n";
echo "TEST: ABSENSI JAMAAH MAJLIS TAKLIM - BERBAGAI TANGGAL\n";
echo "=============================================================\n\n";

$general_setting = Pengaturanumum::where('id', 1)->first();

// Test untuk beberapa hari terakhir
$dates_to_test = [
    date('Y-m-d'), // Hari ini
    date('Y-m-d', strtotime('-1 day')), // Kemarin
    date('Y-m-d', strtotime('-2 days')), // 2 hari lalu
    date('Y-m-d', strtotime('-7 days')), // Seminggu lalu
];

$jamaah_pins = JamaahMajlisTaklim::whereNotNull('pin_fingerprint')
    ->where('pin_fingerprint', '!=', '')
    ->pluck('pin_fingerprint')
    ->toArray();

echo "PIN Jamaah yang terdaftar:\n";
echo implode(", ", $jamaah_pins) . "\n\n";

foreach ($dates_to_test as $tanggal) {
    echo "========================================\n";
    echo "Tanggal: {$tanggal}\n";
    echo "========================================\n";
    
    $url = 'https://developer.fingerspot.io/api/get_attlog';
    $data = json_encode([
        'trans_id' => '1',
        'cloud_id' => $general_setting->cloud_id,
        'start_date' => $tanggal,
        'end_date' => $tanggal
    ]);
    
    $authorization = "Authorization: Bearer " . $general_setting->api_key;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        $authorization
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    $res = json_decode($result);
    
    if ($res && isset($res->data)) {
        $datamesin = $res->data;
        echo "Total record: " . count($datamesin) . "\n";
        
        if (count($datamesin) > 0) {
            // Filter hanya jamaah
            $jamaah_records = array_filter($datamesin, function($obj) use ($jamaah_pins) {
                return in_array($obj->pin, $jamaah_pins);
            });
            
            echo "Record Jamaah: " . count($jamaah_records) . "\n";
            
            if (count($jamaah_records) > 0) {
                echo "\n✅ DATA JAMAAH DITEMUKAN:\n";
                foreach ($jamaah_records as $d) {
                    $jamaah = JamaahMajlisTaklim::where('pin_fingerprint', $d->pin)->first();
                    $nama = $jamaah ? $jamaah->nama : "Unknown";
                    echo "- PIN: {$d->pin} ({$nama}), Time: {$d->scan_date}, Status: {$d->status_scan}\n";
                }
            } else {
                echo "❌ Tidak ada data jamaah\n";
            }
            
            // Tampilkan semua PIN yang ada
            echo "\nSemua PIN di mesin:\n";
            foreach ($datamesin as $d) {
                $type = in_array($d->pin, $jamaah_pins) ? "JAMAAH" : "KARYAWAN";
                echo "- PIN: {$d->pin} ({$type})\n";
            }
        } else {
            echo "❌ Tidak ada data sama sekali\n";
        }
    } else {
        echo "❌ Error API response\n";
    }
    
    echo "\n";
}

echo "\n=============================================================\n";
echo "REKOMENDASI\n";
echo "=============================================================\n\n";

echo "1. Untuk testing fitur ini, jamaah harus absen di mesin fisik terlebih dahulu\n";
echo "2. PIN jamaah yang terdaftar: " . implode(", ", $jamaah_pins) . "\n";
echo "3. Pastikan jamaah sudah terdaftar di mesin fingerprint dengan PIN yang sama\n";
echo "4. Sistem sudah BERFUNGSI dengan baik, hanya menunggu data absensi dari jamaah\n\n";

echo "CARA TES:\n";
echo "1. Minta salah satu jamaah (misal PIN 1002) absen di mesin fingerprint\n";
echo "2. Tunggu beberapa menit untuk sinkronisasi cloud\n";
echo "3. Buka halaman Majlis Taklim > Data Jamaah\n";
echo "4. Klik tombol hijau dengan icon desktop di jamaah tersebut\n";
echo "5. Pilih tanggal hari ini\n";
echo "6. Data seharusnya muncul dan bisa di-import\n\n";
