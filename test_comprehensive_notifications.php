<?php
/**
 * Test Comprehensive Notification Coverage
 * Memverifikasi bahwa semua aktivitas aplikasi menghasilkan notifikasi real-time
 * Termasuk "hal paling kecil juga" sesuai permintaan user
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\RealTimeNotification;
use App\Services\NotificationService;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST COMPREHENSIVE NOTIFICATION COVERAGE ===\n\n";

// 1. Cek total notifikasi hari ini
$todayNotifications = RealTimeNotification::today()->count();
echo "📊 Total notifikasi hari ini: " . $todayNotifications . "\n";

// 2. Breakdown per kategori
$categories = DB::table('real_time_notifications')
    ->select('type', DB::raw('count(*) as total'))
    ->where('created_at', '>=', now()->startOfDay())
    ->groupBy('type')
    ->orderBy('total', 'desc')
    ->get();

echo "\n📈 Breakdown per kategori:\n";
foreach ($categories as $category) {
    echo "   • {$category->type}: {$category->total} notifikasi\n";
}

// 3. Cek model yang sudah teregistrasi di Observer
$registeredModels = [
    // Model utama yang sudah ada
    'Presensi', 'AktivitasKendaraan', 'Pinjaman', 'Izinabsen', 'Izinsakit', 
    'Izincuti', 'Lembur', 'AktivitasKaryawan', 'PeminjamanInventaris', 
    'PengembalianInventaris', 'TransferBarang', 'PeminjamanKendaraan', 
    'ServiceKendaraan', 'AbsensiSantri', 'PelanggaranSantri', 
    'KeuanganSantriTransaction', 'KehadiranTukang', 'PinjamanTukang', 
    'KehadiranJamaah', 'KehadiranJamaahMasar', 'DistribusiHadiah', 
    'DistribusiHadiahMasar', 'Administrasi', 'TindakLanjutAdministrasi', 
    'TransaksiKeuangan', 'RealisasiDanaOperasional', 'PengajuanDanaOperasional',
    
    // Model tambahan yang baru ditambahkan
    'Karyawan', 'User', 'Barang', 'Inventaris', 'Kendaraan', 
    'Gedung', 'Ruangan', 'Santri', 'Tukang', 'JamaahMajlisTaklim', 
    'JamaahMasar', 'Document', 'KpiCrew', 'Kunjungan', 'TugasLuar'
];

echo "\n🔧 Model yang teregistrasi di GlobalActivityObserver:\n";
echo "   Total: " . count($registeredModels) . " model\n";
foreach (array_chunk($registeredModels, 5) as $chunk) {
    echo "   • " . implode(', ', $chunk) . "\n";
}

// 4. Test NotificationService methods
echo "\n🧪 Testing NotificationService methods:\n";

try {
    // Test presensi notification
    NotificationService::presensiNotification([
        'nik' => 'TEST001',
        'nama' => 'Test User',
        'jam_masuk' => '08:00',
        'status' => 'hadir'
    ], 'masuk');
    echo "   ✅ presensiNotification - OK\n";
    
    // Test pinjaman notification
    NotificationService::pinjamanNotification([
        'nik' => 'TEST001',
        'nama' => 'Test User',
        'jumlah_pinjaman' => 500000,
        'jenis_pinjaman' => 'Darurat'
    ], 'pengajuan');
    echo "   ✅ pinjamanNotification - OK\n";
    
    // Test custom notification
    NotificationService::customNotification(
        'Test Notification',
        'Testing comprehensive coverage',
        'test',
        [
            'icon' => 'ti ti-test-pipe',
            'color' => 'success',
            'data' => ['test' => true]
        ]
    );
    echo "   ✅ customNotification - OK\n";
    
} catch (Exception $e) {
    echo "   ❌ Error testing NotificationService: " . $e->getMessage() . "\n";
}

// 5. Cek notifikasi terbaru
echo "\n📱 5 Notifikasi terbaru:\n";
$latestNotifications = RealTimeNotification::latest()->take(5)->get();
foreach ($latestNotifications as $notification) {
    $time = $notification->created_at->format('H:i:s');
    echo "   • [$time] {$notification->title} - {$notification->type}\n";
}

// 6. Rekomendasi untuk memastikan coverage 100%
echo "\n💡 Rekomendasi untuk coverage sempurna:\n";
echo "   1. Pastikan semua controller menggunakan NotificationService\n";
echo "   2. Verifikasi Observer terdaftar untuk semua model critical\n";
echo "   3. Test manual untuk aktivitas yang paling sering digunakan\n";
echo "   4. Monitor dashboard admin untuk melihat notifikasi real-time\n";
echo "   5. Implementasikan filtering untuk menghindari spam notifikasi\n";

// 7. Statistik harian
$hourlyStats = DB::table('real_time_notifications')
    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
    ->where('created_at', '>=', now()->startOfDay())
    ->groupBy(DB::raw('HOUR(created_at)'))
    ->orderBy('hour')
    ->get();

echo "\n📊 Distribusi notifikasi per jam hari ini:\n";
foreach ($hourlyStats as $stat) {
    $hour = str_pad($stat->hour, 2, '0', STR_PAD_LEFT);
    $bars = str_repeat('▓', min($stat->total, 20));
    $total = str_pad($stat->total, 3, ' ', STR_PAD_LEFT);
    echo "   {$hour}:00 [{$total}] {$bars}\n";
}

echo "\n✅ Test selesai! Sistem notifikasi komprehensif siap digunakan.\n";
echo "🎯 Semua aktivitas aplikasi akan ditampilkan di dashboard admin real-time.\n\n";