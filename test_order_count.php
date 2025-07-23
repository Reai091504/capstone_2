<?php
include 'config.php';

// Test the exact query
$date = date('Y-m-d');
echo "Testing for date: $date\n";

$result = $conn->query("
    SELECT COUNT(*) as total 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id 
    WHERE DATE(o.date_created) = '$date' AND o.paid = 1 AND o.status != 4
");

$count = $result->fetch_assoc()['total'];
echo "Products ordered today: $count\n";

// Let's also check raw data
$raw = $conn->query("
    SELECT ol.*, o.date_created, o.paid, o.status 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id 
    WHERE DATE(o.date_created) = '$date'
");
echo "Raw order_list items for today: " . $raw->num_rows . "\n";

// Check all orders for today
$all_orders = $conn->query("SELECT * FROM orders WHERE DATE(date_created) = '$date'");
echo "All orders today: " . $all_orders->num_rows . "\n";
?>
