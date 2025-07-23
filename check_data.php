<?php
include 'config.php';

echo "=== Checking Orders Data ===\n";
$orders = $conn->query("SELECT id, date_created FROM orders");
echo "Total orders: " . $orders->num_rows . "\n";
while($row = $orders->fetch_assoc()) {
    echo "Order ID: {$row['id']}, Date: {$row['date_created']}\n";
}

echo "\n=== Checking Order List Data ===\n";
$order_list = $conn->query("SELECT * FROM order_list");
echo "Total order_list items: " . $order_list->num_rows . "\n";
while($row = $order_list->fetch_assoc()) {
    echo "Order List ID: {$row['id']}, Order ID: {$row['order_id']}, Product ID: {$row['product_id']}, Quantity: {$row['quantity']}\n";
}

echo "\n=== Checking Joined Data ===\n";
$joined = $conn->query("
    SELECT ol.id, ol.order_id, ol.product_id, ol.quantity, o.date_created, o.paid, o.status 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id
");
echo "Total joined items: " . $joined->num_rows . "\n";
while($row = $joined->fetch_assoc()) {
    echo "Order List ID: {$row['id']}, Order ID: {$row['order_id']}, Date: {$row['date_created']}, Paid: {$row['paid']}, Status: {$row['status']}\n";
}

echo "\n=== Checking Today's Data ===\n";
$today = date('Y-m-d');
$today_data = $conn->query("
    SELECT COUNT(*) as total 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id 
    WHERE DATE(o.date_created) = '$today'
");
echo "Today's order_list items (without filters): " . $today_data->fetch_assoc()['total'] . "\n";
?>
