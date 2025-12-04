<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

// Check data
$yayasan = \App\Models\YayasanMasar::where('status_aktif', 1)->count();
echo "Yayasan aktif: $yayasan\n";

$majlis = \App\Models\JamaahMajlisTaklim::count();
echo "Majlis: $majlis\n";

// Test the indexKaryawan response
$controller = new \App\Http\Controllers\JamaahMajlisTaklimController();
$request = new \Illuminate\Http\Request();
$request->merge(['ajax' => true]);
$response = $controller->indexKaryawan($request);

$data = json_decode($response->getContent(), true);
echo "\nResponse: ";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
