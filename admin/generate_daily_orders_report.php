<?php
include '../config.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Get today's order data from order_list
$report_data = $conn->query("
    SELECT 
        o.id as order_id,
        o.date_created,
        o.delivery_address,
        o.amount as order_total,
        ol.product_id,
        p.product_name,
        ol.quantity,
        ol.price as item_price,
        ol.total as item_total,
        ol.size,
        ol.unit,
        c.firstname,
        c.lastname,
        c.email,
        c.contact
    FROM order_list ol
    JOIN orders o ON ol.order_id = o.id
    JOIN products p ON ol.product_id = p.id
    JOIN clients c ON o.client_id = c.id
    WHERE DATE(o.date_created) = '$date' AND o.paid = 1 AND o.status != 4
    ORDER BY o.date_created DESC, p.product_name ASC
");

// Generate Excel-compatible CSV report
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="daily_orders_report_' . $date . '.csv"');

$output = fopen('php://output', 'w');

// Add CSV headers with BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, [
    'Order ID',
    'Order Date',
    'Customer Name',
    'Customer Email',
    'Customer Phone',
    'Delivery Address',
    'Product Name',
    'Size',
    'Unit',
    'Quantity',
    'Item Price',
    'Item Total',
    'Order Total'
]);

// Add data rows
$order_totals = [];
while($row = $report_data->fetch_assoc()) {
    fputcsv($output, [
        $row['order_id'],
        $row['date_created'],
        $row['firstname'] . ' ' . $row['lastname'],
        $row['email'],
        $row['contact'],
        $row['delivery_address'],
        $row['product_name'],
        $row['size'],
        $row['unit'],
        $row['quantity'],
        $row['item_price'],
        $row['item_total'],
        $row['order_total']
    ]);
    
    // Track order totals for summary
    $order_totals[$row['order_id']] = $row['order_total'];
}

// Add summary section
fputcsv($output, []); // Empty row
fputcsv($output, ['=== DAILY SUMMARY ===']);
fputcsv($output, ['Date', $date]);
fputcsv($output, ['Total Orders', count($order_totals)]);
fputcsv($output, ['Total Items Sold', $report_data->num_rows]);
fputcsv($output, ['Total Revenue', '₱' . number_format(array_sum($order_totals), 2)]);

fclose($output);
exit;
?>
