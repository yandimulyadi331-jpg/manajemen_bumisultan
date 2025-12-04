<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST SISTEM INVENTARIS MULTI-UNIT ===\n\n";

// 1. Cek data inventaris yang ada
echo "1. Data Inventaris yang Ada:\n";
echo str_repeat("-", 70) . "\n";
$inventaris = App\Models\Inventaris::orderBy('id', 'desc')->take(5)->get();
foreach ($inventaris as $item) {
    echo sprintf(
        "ID: %d | Kode: %s | Nama: %s | Jumlah: %d | Tracking: %s\n",
        $item->id,
        $item->kode_inventaris,
        $item->nama_barang,
        $item->jumlah,
        $item->tracking_per_unit ? 'Ya' : 'Tidak'
    );
}
echo "\n";

// 2. Pilih inventaris pertama dan aktifkan tracking per unit
echo "2. Aktifkan Tracking Per Unit pada Inventaris Pertama:\n";
echo str_repeat("-", 70) . "\n";
$inventaris1 = App\Models\Inventaris::first();
if ($inventaris1) {
    $inventaris1->update([
        'tracking_per_unit' => true,
        'jumlah_unit' => 5
    ]);
    echo "✓ Inventaris '{$inventaris1->nama_barang}' berhasil diaktifkan tracking per unit\n";
    echo "  Jumlah unit yang akan dibuat: 5 unit\n\n";
    
    // 3. Buat unit-unit detail
    echo "3. Membuat 5 Unit Detail:\n";
    echo str_repeat("-", 70) . "\n";
    
    // Buat batch terlebih dahulu
    $batch = App\Models\InventarisUnit::create([
        'inventaris_id' => $inventaris1->id,
        'nama_batch' => 'Batch Pertama - ' . $inventaris1->nama_barang,
        'jumlah_unit_batch' => 5,
        'tanggal_masuk' => now(),
        'lokasi_awal' => 'Gudang Utama',
        'keterangan' => 'Batch awal untuk testing sistem multi-unit',
        'created_by' => 1
    ]);
    
    echo "✓ Batch dibuat: {$batch->kode_batch} - {$batch->nama_batch}\n\n";
    
    // Buat 5 detail unit
    $kondisiList = ['baik', 'baik', 'baik', 'rusak_ringan', 'baik'];
    $lokasiList = ['Gudang Utama', 'Gudang Utama', 'Ruang Kantor', 'Gudang Utama', 'Ruang Kantor'];
    
    for ($i = 1; $i <= 5; $i++) {
        $unit = App\Models\InventarisDetailUnit::create([
            'inventaris_id' => $inventaris1->id,
            'inventaris_unit_id' => $batch->id,
            'kondisi' => $kondisiList[$i - 1],
            'status' => $kondisiList[$i - 1] === 'baik' ? 'tersedia' : 'maintenance',
            'lokasi' => $lokasiList[$i - 1],
            'catatan' => "Unit ke-{$i} - Testing sistem",
            'created_by' => 1
        ]);
        
        echo sprintf(
            "  Unit #%d: %s | Kondisi: %s | Status: %s | Lokasi: %s\n",
            $i,
            $unit->kode_unit,
            $unit->kondisi,
            $unit->status,
            $unit->lokasi
        );
    }
    echo "\n";
    
    // 4. Tampilkan ringkasan status
    echo "4. Ringkasan Status Unit:\n";
    echo str_repeat("-", 70) . "\n";
    $totalUnits = $inventaris1->detailUnits()->count();
    $tersedia = $inventaris1->detailUnitsTersedia()->count();
    $dipinjam = $inventaris1->detailUnitsDipinjam()->count();
    $maintenance = $inventaris1->detailUnits()->where('status', 'maintenance')->count();
    
    echo "Total Unit: {$totalUnits}\n";
    echo "Tersedia: {$tersedia}\n";
    echo "Dipinjam: {$dipinjam}\n";
    echo "Maintenance: {$maintenance}\n\n";
    
    // 5. Tampilkan history
    echo "5. History Unit:\n";
    echo str_repeat("-", 70) . "\n";
    $history = App\Models\InventarisUnitHistory::with('detailUnit')
        ->whereHas('detailUnit', function($q) use ($inventaris1) {
            $q->where('inventaris_id', $inventaris1->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    foreach ($history as $h) {
        echo sprintf(
            "[%s] %s - %s | %s\n",
            $h->created_at->format('d/m/Y H:i'),
            $h->detailUnit ? $h->detailUnit->kode_unit : 'N/A',
            $h->jenis_aktivitas,
            $h->keterangan
        );
    }
    
} else {
    echo "✗ Tidak ada data inventaris!\n";
}

echo "\n=== SELESAI ===\n";
