<?php
include '../config.php';

// Get detailed stock out products
$stock_out_details = $conn->query("
    SELECT 
        i.id,
        p.product_name,
        i.quantity,
        i.price,
        i.size,
        i.unit,
        DATE(i.date_updated) as stock_out_date
    FROM inventory i
    JOIN products p ON i.product_id = p.id
    WHERE i.quantity <= 0
    ORDER BY p.product_name ASC
");

$data = [
    'total_count' => $stock_out_details->num_rows,
    'data' => []
];

while($row = $stock_out_details->fetch_assoc()) {
    $data['data'][] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
