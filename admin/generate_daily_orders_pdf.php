<?php
include '../config.php';
require_once('../classes/fpdf/fpdf.php');

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

// Calculate totals
$total_orders = 0;
$total_items = 0;
$total_revenue = 0;
$orders = [];

while($row = $report_data->fetch_assoc()) {
    $orders[$row['order_id']][] = $row;
    $total_revenue += $row['order_total'];
    $total_items += $row['quantity'];
}

$total_orders = count($orders);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Daily Orders Report', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Date: ' . date('F j, Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(10);

// Summary
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Daily Summary', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Total Orders: ' . $total_orders, 0, 1);
$pdf->Cell(0, 8, 'Total Items Sold: ' . $total_items, 0, 1);
$pdf->Cell(0, 8, 'Total Revenue: ₱' . number_format($total_revenue, 2), 0, 1);
$pdf->Ln(10);

// Order Details
foreach($orders as $order_id => $order_items) {
    $order = $order_items[0];
    
    // Order Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Order #' . $order_id, 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Date: ' . date('M j, Y H:i', strtotime($order['date_created'])), 0, 1);
    $pdf->Cell(0, 6, 'Customer: ' . $order['firstname'] . ' ' . $order['lastname'], 0, 1);
    $pdf->Cell(0, 6, 'Email: ' . $order['email'], 0, 1);
    $pdf->Cell(0, 6, 'Phone: ' . $order['contact'], 0, 1);
    $pdf->Cell(0, 6, 'Delivery: ' . $order['delivery_address'], 0, 1);
    $pdf->Ln(5);
    
    // Table Header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(80, 8, 'Product', 1);
    $pdf->Cell(20, 8, 'Size', 1);
    $pdf->Cell(15, 8, 'Qty', 1);
    $pdf->Cell(25, 8, 'Price', 1);
    $pdf->Cell(25, 8, 'Total', 1);
    $pdf->Ln();
    
    // Table Data
    $pdf->SetFont('Arial', '', 9);
    $order_total = 0;
    foreach($order_items as $item) {
        $pdf->Cell(80, 8, $item['product_name'], 1);
        $pdf->Cell(20, 8, $item['size'], 1);
        $pdf->Cell(15, 8, $item['quantity'], 1);
        $pdf->Cell(25, 8, '₱' . number_format($item['item_price'], 2), 1);
        $pdf->Cell(25, 8, '₱' . number_format($item['item_total'], 2), 1);
        $pdf->Ln();
        $order_total += $item['item_total'];
    }
    
    // Order Total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(140, 8, 'Order Total:', 1);
    $pdf->Cell(25, 8, '₱' . number_format($order['order_total'], 2), 1);
    $pdf->Ln(15);
}

// Footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Generated on ' . date('F j, Y H:i:s'), 0, 1, 'C');

// Output PDF
$pdf->Output('D', 'daily_orders_report_' . $date . '.pdf');
exit;
?>
