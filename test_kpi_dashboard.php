<?php
/**
 * Test KPI Crew Data untuk Dashboard
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\KpiCrew;
use App\Models\Karyawan;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🏆 TESTING KPI CREW DATA\n";
echo "========================\n\n";

try {
    $bulanIni = date('n'); // 1-12
    $tahunIni = date('Y');
    
    echo "📅 Periode: Bulan $bulanIni Tahun $tahunIni\n\n";
    
    // Check total KPI data bulan ini
    $totalKpi = KpiCrew::where('bulan', $bulanIni)
        ->where('tahun', $tahunIni)
        ->count();
    
    echo "📊 Total KPI Crew bulan ini: $totalKpi\n\n";
    
    if ($totalKpi == 0) {
        echo "⚠️ Tidak ada data KPI untuk bulan ini!\n";
        echo "💡 Membuat sample data untuk testing...\n\n";
        
        // Get some karyawan for sample data
        $karyawanSample = Karyawan::limit(10)->get();
        
        if ($karyawanSample->count() > 0) {
            foreach ($karyawanSample as $index => $karyawan) {
                $kpiData = [
                    'nik' => $karyawan->nik,
                    'bulan' => $bulanIni,
                    'tahun' => $tahunIni,
                    'kehadiran_count' => rand(20, 25),
                    'aktivitas_count' => rand(10, 20),
                    'perawatan_count' => rand(5, 15),
                    'kehadiran_point' => rand(40, 50),
                    'aktivitas_point' => rand(20, 30),
                    'perawatan_point' => rand(10, 20),
                ];
                
                $kpiData['total_point'] = $kpiData['kehadiran_point'] + 
                                         $kpiData['aktivitas_point'] + 
                                         $kpiData['perawatan_point'];
                
                KpiCrew::create($kpiData);
                
                echo "✅ Created KPI for {$karyawan->nama_karyawan} - Total: {$kpiData['total_point']} points\n";
            }
            
            echo "\n✅ Sample data created successfully!\n\n";
        }
    }
    
    // Get top 10 KPI
    echo "🏅 TOP 10 KPI CREW:\n";
    echo "===================\n\n";
    
    $topKpi = KpiCrew::with(['karyawan' => function($query) {
            $query->select('nik', 'nama_karyawan', 'kode_jabatan')
                ->with(['jabatan' => function($q) {
                    $q->select('kode_jabatan', 'nama_jabatan');
                }]);
        }])
        ->where('bulan', $bulanIni)
        ->where('tahun', $tahunIni)
        ->orderBy('total_point', 'desc')
        ->limit(10)
        ->get();
    
    if ($topKpi->count() > 0) {
        foreach ($topKpi as $index => $kpi) {
            $ranking = $index + 1;
            $icon = $ranking == 1 ? '🥇' : ($ranking == 2 ? '🥈' : ($ranking == 3 ? '🥉' : '🏅'));
            
            $namaKaryawan = $kpi->karyawan ? $kpi->karyawan->nama_karyawan : 'Unknown';
            $jabatan = $kpi->karyawan && $kpi->karyawan->jabatan ? $kpi->karyawan->jabatan->nama_jabatan : '-';
            
            echo "$icon Rank $ranking: {$kpi->nik} - $namaKaryawan\n";
            echo "   Jabatan: $jabatan\n";
            echo "   Kehadiran: {$kpi->kehadiran_count}x ({$kpi->kehadiran_point} pt)\n";
            echo "   Aktivitas: {$kpi->aktivitas_count}x ({$kpi->aktivitas_point} pt)\n";
            echo "   Perawatan: {$kpi->perawatan_count}x ({$kpi->perawatan_point} pt)\n";
            echo "   📊 TOTAL: {$kpi->total_point} POINTS\n\n";
        }
    } else {
        echo "❌ Tidak ada data KPI crew\n";
    }
    
    echo "✅ KPI DATA READY FOR DASHBOARD!\n";
    echo "Dashboard akan menampilkan top 10 KPI crew bulan ini.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🎯 Test selesai!\n";