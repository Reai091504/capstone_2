<?php
require_once('config.php');

echo "=== Stock Out Debug Test ===\n";

// Test 1: Check total inventory count
$result = $conn->query("SELECT COUNT(*) as total FROM inventory");
$total = $result->fetch_assoc()['total'];
echo "Total inventory items: " . $total . "\n";

// Test 2: Check stock out count
$result = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE quantity <= 0");
$stock_out = $result->fetch_assoc()['total'];
echo "Stock out items: " . $stock_out . "\n";

// Test 3: Show all inventory data
echo "\n=== All Inventory Data ===\n";
$result = $conn->query("SELECT i.id, p.product_name, i.quantity FROM inventory i JOIN products p ON i.product_id = p.id ORDER BY i.quantity ASC");
while($row = $result->fetch_assoc()) {
    echo "Product: " . $row['product_name'] . ", Quantity: " . $row['quantity'] . "\n";
}

// Test 4: Check if there are any NULL quantities
$result = $conn->query("SELECT COUNT(*) as null_count FROM inventory WHERE quantity IS NULL");
$null_count = $result->fetch_assoc()['null_count'];
echo "\nNULL quantities: " . $null_count . "\n";

echo "\n=== Test Complete ===\n";
?>
