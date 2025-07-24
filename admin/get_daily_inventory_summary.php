<?php
include '../config.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Get daily inventory summary
$summary = $conn->query("
    SELECT 
        ih.product_id,
        ih.product_name,
        ih.quantity,
        ih.unit,
        ih.price,
        ih.date_recorded,
        p.description,
        COALESCE(previous.quantity, 0) as previous_day_quantity,
        (ih.quantity - COALESCE(previous.quantity, 0)) as quantity_change
    FROM inventory_history ih
    JOIN products p ON ih.product_id = p.id
    LEFT JOIN inventory_history previous ON ih.product_id = previous.product_id 
        AND previous.date_recorded = DATE_SUB(ih.date_recorded, INTERVAL 1 DAY)
    WHERE ih.date_recorded = ?
    ORDER BY ih.product_name ASC
");

$daily_data = [];
$total_products = 0;
$total_value = 0;
$stock_out_count = 0;

while($row = $summary->fetch_assoc()) {
    $total_products++;
    $total_value += ($row['quantity'] * $row['price']);
    
    if($row['quantity'] <= 0) {
        $stock_out_count++;
    }
    
    $daily_data[] = [
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'unit' => $row['unit'],
        'price' => number_format($row['price'], 2),
        'total_value' => number_format($row['quantity'] * $row['price'], 2),
        'previous_day_quantity' => $row['previous_day_quantity'],
        'quantity_change' => $row['quantity_change'],
        'description' => $row['description']
    ];
}

// Get summary statistics
$stats = [
    'total_products' => $total_products,
    'total_inventory_value' => number_format($total_value, 2),
    'stock_out_count' => $stock_out_count,
    'date' => $date
];

header('Content-Type: application/json');
echo json_encode([
    'summary' => $stats,
    'products' => $daily_data,
    'generated_at' => date('Y-m-d H:i:s')
]);
?>
