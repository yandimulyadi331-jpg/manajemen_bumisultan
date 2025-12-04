<?php
// Test controller response
require 'vendor/autoload.php';

// Setup Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Create a fake request that looks like AJAX
$uri = '/majlistaklim-karyawan/jamaah?ajax=true';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = $uri;
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

// Capture output
ob_start();

try {
    // Test the route manually
    $controller = app(\App\Http\Controllers\JamaahMajlisTaklimController::class);
    $request = new \Illuminate\Http\Request();
    $request->merge(['ajax' => true]);
    $request->server->set('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
    
    // Call the method
    $response = $controller->indexKaryawan($request);
    
    // Get content
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    echo "=== CONTROLLER RESPONSE ===\n";
    echo "Success: " . ($data['success'] ? 'YES' : 'NO') . "\n";
    echo "Total Data: " . count($data['data']) . "\n";
    echo "\nFirst 3 records:\n";
    
    foreach (array_slice($data['data'], 0, 3) as $item) {
        echo "- " . $item['nama_jamaah'] . " (" . $item['jenis_kelamin'] . ")\n";
    }
    
    // Count by gender
    $laki = array_filter($data['data'], fn($x) => strtoupper($x['jenis_kelamin']) === 'L');
    $perempuan = array_filter($data['data'], fn($x) => strtoupper($x['jenis_kelamin']) === 'P');
    
    echo "\nGender breakdown:\n";
    echo "- Laki-laki: " . count($laki) . "\n";
    echo "- Perempuan: " . count($perempuan) . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

ob_end_clean();
?>
