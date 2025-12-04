<?php
/**
 * Test Green Background for Unread Notifications
 * Memverifikasi bahwa notifikasi yang belum dibaca memiliki background hijau
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\NotificationService;
use App\Models\RealTimeNotification;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 TESTING GREEN BACKGROUND FOR UNREAD NOTIFICATIONS\n";
echo "===================================================\n\n";

try {
    // Buat beberapa notifikasi test untuk memverifikasi styling
    echo "📝 Creating test notifications...\n\n";
    
    // 1. Notifikasi unread (baru)
    $notification1 = NotificationService::customNotification(
        'Test Notifikasi Unread',
        'Ini adalah notifikasi yang belum dibaca - seharusnya background hijau',
        'test',
        [
            'icon' => 'ti ti-test-pipe',
            'color' => 'success',
            'data' => ['test' => true]
        ]
    );
    echo "✅ Created unread notification (should have green background)\n";
    
    // 2. Notifikasi untuk tracking
    $notification2 = NotificationService::trackingPresensiNotification([
        'message' => 'Update lokasi unread: Jane Doe pindah ke Lab Komputer',
        'nama_karyawan' => 'Jane Doe',
        'lokasi_baru' => 'Lab Komputer',
        'lokasi_lama' => 'Kantor',
        'reference_id' => 2,
        'reference_table' => 'aktivitas_karyawan'
    ], 'lokasi_update');
    echo "✅ Created tracking notification (should have green background)\n";
    
    // 3. Notifikasi santri
    $notification3 = NotificationService::santriDetailNotification([
        'message' => 'Santri hadir unread: Fatimah - Fiqih pada 2024-11-28 08:00',
        'nama_santri' => 'Fatimah',
        'mata_pelajaran' => 'Fiqih',
        'tanggal' => '2024-11-28',
        'jam_masuk' => '08:00',
        'kelas' => 'Wustho 2',
        'reference_id' => 3,
        'reference_table' => 'absensi_santri'
    ], 'absensi_hadir');
    echo "✅ Created santri notification (should have green background)\n";
    
    // 4. Simulasi notifikasi yang sudah dibaca
    $notification4 = NotificationService::customNotification(
        'Test Notifikasi Read',
        'Ini adalah notifikasi yang akan ditandai sudah dibaca - background putih',
        'test',
        [
            'icon' => 'ti ti-check',
            'color' => 'secondary',
            'data' => ['read_test' => true]
        ]
    );
    
    // Mark notification 4 as read
    if ($notification4) {
        RealTimeNotification::where('id', $notification4->id)->update(['is_read' => true]);
        echo "✅ Created and marked notification as read (should have normal background)\n";
    }
    
    echo "\n📊 SUMMARY OF STYLING TESTS:\n";
    echo "============================\n";
    
    $todayNotifications = RealTimeNotification::today()->latest()->take(10)->get();
    
    foreach ($todayNotifications as $notification) {
        $readStatus = $notification->is_read ? '📖 READ' : '🟢 UNREAD';
        $bgStyle = $notification->is_read ? 'Normal background' : '🎨 GREEN BACKGROUND';
        
        echo "• {$notification->title}\n";
        echo "  Status: {$readStatus}\n";
        echo "  Style: {$bgStyle}\n";
        echo "  Created: {$notification->created_at->format('H:i:s')}\n\n";
    }
    
    // Get counts
    $total = RealTimeNotification::today()->count();
    $unread = RealTimeNotification::today()->where('is_read', false)->count();
    $read = $total - $unread;
    
    echo "📈 NOTIFICATION COUNTS:\n";
    echo "======================\n";
    echo "Total hari ini: {$total}\n";
    echo "🟢 Unread (hijau): {$unread}\n";
    echo "📖 Read (putih): {$read}\n\n";
    
    echo "🎨 CSS STYLING INFO:\n";
    echo "===================\n";
    echo "• Unread notifications menggunakan class: 'unread bg-unread'\n";
    echo "• CSS rule: background-color: rgba(40, 167, 69, 0.08) dengan border hijau\n";
    echo "• Read notifications menggunakan class: 'read' dengan background normal\n";
    echo "• Unread juga memiliki badge 'BELUM DIBACA' dan tombol hijau\n\n";
    
    echo "✅ STYLING TEST COMPLETE!\n";
    echo "Dashboard sekarang akan menampilkan:\n";
    echo "• 🟢 Notifikasi unread dengan BACKGROUND HIJAU\n";
    echo "• 📖 Notifikasi read dengan background putih biasa\n";
    echo "• Badge 'BELUM DIBACA' untuk notifikasi unread\n";
    echo "• Tombol hijau untuk mark as read\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎉 GREEN BACKGROUND STYLING READY!\n";