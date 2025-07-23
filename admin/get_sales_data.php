<?php
// admin/get_sales_data.php
include '../config.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$query = "SELECT 
            ol.id as order_list_id,
            o.client_id,
            p.product_name as product_name,
            SUM(ol.quantity) as total_quantity,
            SUM(ol.price * ol.quantity) as total_sales
          FROM orders o
          LEFT JOIN order_list ol ON o.id = ol.order_id
          LEFT JOIN products p ON ol.product_id = p.id
          WHERE o.status != 4 AND o.paid = 1 AND DATE(o.date_created) = '$date'
          GROUP BY ol.product_id
          ORDER BY total_quantity DESC";

$result = $conn->query($query);

$labels = [];
$quantities = [];
$sales = [];
$client_ids = [];
$order_list_ids = [];
$overall_quantity = 0;
$overall_sales = 0;

while ($row = $result->fetch_assoc()) {
    $order_list_ids[] = $row['order_list_id'];
    $client_ids[] = $row['client_id'];
    $labels[] = $row['product_name'];
    $quantities[] = intval($row['total_quantity']);
    $sales[] = floatval($row['total_sales']);
    $overall_quantity += intval($row['total_quantity']);
    $overall_sales += floatval($row['total_sales']);
}

$data = [
    'order_list_ids' => $order_list_ids,
    'client_ids' => $client_ids,
    'labels' => $labels,
    'quantities' => $quantities,
    'sales' => $sales,
    'overall_quantity' => $overall_quantity,
    'overall_sales' => $overall_sales
];

header('Content-Type: application/json');
echo json_encode($data);
?> 