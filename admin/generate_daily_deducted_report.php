<?php
include '../config.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Query to get items deducted today with date and time based on orders with status = 1 and paid = 1
$query = "
    SELECT 
        p.product_name,
        ol.quantity,
        o.date_created as deducted_datetime
    FROM orders o
    JOIN order_list ol ON o.id = ol.order_id
    JOIN products p ON ol.product_id = p.id
    WHERE DATE(o.date_created) = ?
    AND o.status = 1
    AND o.paid = 1
    ORDER BY o.date_created DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $date);
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="daily_deducted_items_report_' . $date . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Product Name', 'Quantity', 'Date and Time Deducted']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['product_name'], $row['quantity'], $row['deducted_datetime']]);
}

fclose($output);
exit;
?>
