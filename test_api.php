<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000/majlistaklim-karyawan/jamaah?ajax=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Requested-With: XMLHttpRequest',
    'Cookie: XSRF-TOKEN=test; PHPSESSID=test'
]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n\n";
echo "Response:\n";
echo substr($response, 0, 500) . "\n\n";

$data = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "Valid JSON\n";
    echo "Success: " . ($data['success'] ? 'YES' : 'NO') . "\n";
    if (isset($data['data'])) {
        echo "Total records: " . count($data['data']) . "\n";
        if (count($data['data']) > 0) {
            echo "First record: " . $data['data'][0]['nama_jamaah'] . "\n";
        }
    }
} else {
    echo "Invalid JSON: " . json_last_error_msg() . "\n";
}
?>
