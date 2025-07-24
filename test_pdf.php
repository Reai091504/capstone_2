<?php
// Simple test to check if FPDF is available
require_once('config.php');

// Check if FPDF exists
$fpdf_paths = [
    'classes/fpdf/fpdf.php',
    'libs/fpdf/fpdf.php',
    '../classes/fpdf/fpdf.php',
    '../libs/fpdf/fpdf.php'
];

$fpdf_found = false;
foreach($fpdf_paths as $path) {
    if (file_exists($path)) {
        echo "FPDF found at: $path\n";
        $fpdf_found = true;
        break;
    }
}

if (!$fpdf_found) {
    echo "FPDF not found in expected locations\n";
    echo "Current directory: " . getcwd() . "\n";
    echo "Files in classes: " . implode(", ", scandir('classes')) . "\n";
    echo "Files in libs: " . implode(", ", scandir('libs')) . "\n";
}

// Test database connection
$result = $conn->query("SELECT COUNT(*) as count FROM order_list LIMIT 1");
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "Database connection OK, order_list has $count records\n";
} else {
    echo "Database error: " . $conn->error . "\n";
}
?>
