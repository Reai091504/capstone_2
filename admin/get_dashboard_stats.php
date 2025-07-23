<?php
include '../config.php';

// Get total products
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Get stock out today (products ordered today from order_list)
$stock_out_today = $conn->query("
    SELECT COUNT(*) as total 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id 
    WHERE DATE(o.date_created) = CURDATE() AND o.paid = 1 AND o.status != 4
")->fetch_assoc()['total'];

// Get total users from clients table
$total_users = $conn->query("SELECT COUNT(*) as total FROM clients")->fetch_assoc()['total'];

// Get orders today (count of distinct orders)
$orders_today = $conn->query("SELECT COUNT(*) as total FROM orders WHERE DATE(date_created) = CURDATE() AND paid = 1 AND status != 4")->fetch_assoc()['total'];

// Get most purchased item today
$most_purchased_item_row = $conn->query("
    SELECT p.product_name, SUM(ol.quantity) as total_quantity
    FROM order_list ol
    JOIN orders o ON ol.order_id = o.id
    JOIN products p ON ol.product_id = p.id
    WHERE DATE(o.date_created) = CURDATE() AND o.paid = 1 AND o.status != 4
    GROUP BY ol.product_id
    ORDER BY total_quantity DESC
    LIMIT 1
")->fetch_assoc();

$most_purchased_item = $most_purchased_item_row ? $most_purchased_item_row['product_name'] : 'N/A';

$data = [
    'total_products' => $total_products,
    'orders_today' => $orders_today,
    'total_users' => $total_users,
    'stock_out_today' => $stock_out_today, // Now shows daily order_list count
    'most_purchased_item' => $most_purchased_item
];

header('Content-Type: application/json');
echo json_encode($data);
?>
