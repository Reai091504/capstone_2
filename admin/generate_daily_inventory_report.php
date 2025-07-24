<?php
include '../config.php';
require_once('../classes/Master.php');

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Get daily inventory report data
$report_data = $conn->query("
    SELECT 
        ih.product_id,
        ih.product_name,
        ih.quantity,
        ih.unit,
        ih.price,
        (ih.quantity * ih.price) as total_value,
        p.description,
        COALESCE(previous.quantity, 0) as previous_quantity,
        (ih.quantity - COALESCE(previous.quantity, 0)) as change_in_quantity,
        CASE 
            WHEN ih.quantity <= 0 THEN 'OUT OF STOCK'
            WHEN ih.quantity <= 5 THEN 'LOW STOCK'
            ELSE 'IN STOCK'
        END as stock_status
    FROM inventory_history ih
    JOIN products p ON ih.product_id = p.id
    LEFT JOIN inventory_history previous ON ih.product_id = previous.product_id 
        AND previous.date_recorded = DATE_SUB(ih.date_recorded, INTERVAL 1 DAY)
    WHERE ih.date_recorded = ?
    ORDER BY ih.product_name ASC
");

// Generate CSV report
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="daily_inventory_report_' . $date . '.csv"');

$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'Product ID',
    'Product Name',
    'Description',
    'Current Quantity',
    'Unit',
    'Price',
    'Total Value',
    'Previous Day Quantity',
    'Change in Quantity',
    'Stock Status',
    'Date'
]);

// Add data rows
while($row = $report_data->fetch_assoc()) {
    fputcsv($output, [
        $row['product_id'],
        $row['product_name'],
        $row['description'],
        $row['quantity'],
        $row['unit'],
        $row['price'],
        $row['total_value'],
        $row['previous_quantity'],
        $row['change_in_quantity'],
        $row['stock_status'],
        $date
    ]);
}

fclose($output);
exit;
?>
