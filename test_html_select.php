<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\YayasanMasar;

echo "=== COBA GENERATE HTML SELECT ===\n\n";

$jamaah_list = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get();

echo "<select id='jamaah_id' name='jamaah_id'>\n";
echo "  <option value=''>-- Pilih Jamaah --</option>\n";
foreach ($jamaah_list as $jamaah) {
    echo "  <option value='{$jamaah->id}'>{$jamaah->nama}</option>\n";
}
echo "</select>\n";

echo "\nTotal options: " . ($jamaah_list->count() + 1) . "\n";
