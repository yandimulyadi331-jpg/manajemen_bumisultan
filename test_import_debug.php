<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Log;

// Aktifkan logging untuk import
Log::info('=== TEST IMPORT DEBUG ===');

// Cek apakah ada file Excel di storage
$storagePath = storage_path('app');
echo "📁 Storage path: $storagePath\n\n";

// Lihat error log terakhir
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    echo "📝 Log terakhir (20 baris):\n";
    echo "=====================================\n";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -20);
    echo implode('', $lastLines);
    echo "\n=====================================\n";
}

// Test parsing tanggal
echo "\n🧪 TEST PARSING TANGGAL:\n";

function testParseTanggal($value) {
    try {
        if (empty($value)) {
            return \Carbon\Carbon::now();
        }

        // Format ISO: 2025-01-05
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return \Carbon\Carbon::parse($value);
        }
        
        // Format Indonesia: 05/01/2025
        if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/', $value, $matches)) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $matches[1] . '/' . $matches[2] . '/' . $matches[3]);
        }
        
        // Excel date number
        if (is_numeric($value) && $value > 40000) {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        
        // Try general parse
        return \Carbon\Carbon::parse($value);
        
    } catch (\Exception $e) {
        return \Carbon\Carbon::now();
    }
}

$testDates = [
    '2025-01-05',
    '05/01/2025',
    '5/1/2025',
    45666, // Excel date number untuk 2025-01-05
    null,
    '',
];

foreach ($testDates as $date) {
    $parsed = testParseTanggal($date);
    echo "  Input: " . var_export($date, true) . " => " . $parsed->format('Y-m-d H:i:s') . "\n";
}

echo "\n✅ Test parsing selesai!\n";
