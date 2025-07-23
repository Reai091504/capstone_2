<?php
require_once('config.php');

echo "=== Daily Orders Test ===\n";

// Test today's order_list count
$today = date('Y-m-d');
echo "Testing for date: " . $today . "\n";

$result = $conn->query("
    SELECT COUNT(*) as total 
    FROM order_list ol 
    JOIN orders o ON ol.order_id = o.id 
    WHERE DATE(o.date_created) = CURDATE() AND o.paid = 1 AND o.status != 4
");
$count = $result->fetch_assoc()['total'];
echo "Today's order_list count: " . $count . "\n";

// Show sample data
echo "\n=== Sample Order Data ===\n";
$result = $conn->query("
    SELECT o.id, o.date_created, COUNT(ol.id) as items_count
    FROM orders o
    LEFT JOIN order_list ol ON o.id = ol.order_id
    WHERE DATE(o.date_created) = CURDATE() AND o.paid = 1 AND o.status != 4
    GROUP BY o.id
    ORDER BY o.date_created DESC
    LIMIT 5
");
while($row = $result->fetch_assoc()) {
    echo "Order ID: " . $row['id'] . ", Date: " . $row['date_created'] . ", Items: " . $row['items_count'] . "\n";
}

echo "\n=== Test Complete ===\n";
?>
