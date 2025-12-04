<?php
/**
 * Test Tugas Luar Modal
 * Membuat data test untuk memverifikasi modal tugas luar berfungsi
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\TugasLuar;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 TESTING TUGAS LUAR MODAL\n";
echo "===========================\n\n";

try {
    echo "📝 Creating test tugas luar data...\n\n";
    
    // Hapus data lama hari ini untuk test bersih
    TugasLuar::whereDate('tanggal', date('Y-m-d'))->delete();
    echo "✅ Cleared old test data\n";
    
    // Buat 3 data tugas luar test
    $testData = [
        [
            'kode_tugas' => 'TL' . date('Ymd') . '0001',
            'karyawan_list' => json_encode(['NIK001', 'NIK002']),
            'tanggal' => date('Y-m-d'),
            'tujuan' => 'Bank BNI Cabang Sukabumi',
            'keterangan' => 'Ambil uang untuk pembayaran supplier',
            'waktu_keluar' => '08:30:00',
            'waktu_kembali' => null,
            'status' => 'keluar',
            'dibuat_oleh' => 'admin'
        ],
        [
            'kode_tugas' => 'TL' . date('Ymd') . '0002',
            'karyawan_list' => json_encode(['NIK003']),
            'tanggal' => date('Y-m-d'),
            'tujuan' => 'Kantor Pos Sukabumi',
            'keterangan' => 'Kirim dokumen penting ke Jakarta',
            'waktu_keluar' => '09:15:00',
            'waktu_kembali' => null,
            'status' => 'keluar',
            'dibuat_oleh' => 'admin'
        ],
        [
            'kode_tugas' => 'TL' . date('Ymd') . '0003',
            'karyawan_list' => json_encode(['NIK004', 'NIK005', 'NIK006']),
            'tanggal' => date('Y-m-d'),
            'tujuan' => 'Toko Bangunan Mitra Jaya',
            'keterangan' => 'Beli material untuk renovasi gedung',
            'waktu_keluar' => '10:00:00',
            'waktu_kembali' => null,
            'status' => 'keluar',
            'dibuat_oleh' => 'admin'
        ]
    ];
    
    foreach ($testData as $index => $data) {
        $tugasLuar = TugasLuar::create($data);
        echo "✅ Created tugas luar " . ($index + 1) . ": {$data['kode_tugas']} - {$data['tujuan']}\n";
    }
    
    echo "\n📊 CURRENT DATA STATUS:\n";
    echo "======================\n";
    
    $tugasLuarHariIni = TugasLuar::whereDate('tanggal', date('Y-m-d'))
        ->where('status', 'keluar')
        ->orderBy('waktu_keluar', 'desc')
        ->get();
    
    echo "Total tugas luar hari ini: " . $tugasLuarHariIni->count() . "\n\n";
    
    foreach ($tugasLuarHariIni as $tugas) {
        $karyawanList = json_decode($tugas->karyawan_list, true) ?? [];
        echo "🔹 {$tugas->kode_tugas}\n";
        echo "   Karyawan: " . implode(', ', $karyawanList) . " (" . count($karyawanList) . " orang)\n";
        echo "   Tujuan: {$tugas->tujuan}\n";
        echo "   Waktu Keluar: {$tugas->waktu_keluar}\n";
        echo "   Status: {$tugas->status}\n\n";
    }
    
    echo "🎨 TESTING MODAL STRUCTURE:\n";
    echo "===========================\n";
    
    // Simulasi data yang akan dikirim ke view
    $modalData = $tugasLuarHariIni->map(function($item) {
        return [
            'kode_tugas' => $item->kode_tugas,
            'karyawan_list' => json_decode($item->karyawan_list, true),
            'tujuan' => $item->tujuan,
            'keterangan' => $item->keterangan,
            'waktu_keluar' => $item->waktu_keluar,
            'status' => $item->status
        ];
    })->toArray();
    
    echo "JSON data untuk JavaScript:\n";
    echo json_encode($modalData, JSON_PRETTY_PRINT);
    
    echo "\n✅ MODAL TEST READY!\n";
    echo "Dashboard card sekarang akan menampilkan: " . $tugasLuarHariIni->count() . " tugas luar\n";
    echo "Klik card 'Karyawan Tugas Luar' untuk test modal\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎉 TUGAS LUAR TEST COMPLETE!\n";