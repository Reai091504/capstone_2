<?php
require_once 'libs/fpdf/fpdf.php';

// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pet_shop_db';

function outputPdfError($message, $date) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Sales Report for ' . $date, 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 10, 'Error: ' . $message, 0, 'C');
    $pdf->Output('D', 'sales_report_' . $date . '.pdf');
    exit;
}

// Date range support
$use_range = false;
if (isset($_GET['start']) && isset($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $use_range = true;
    $date_label = $start . ' to ' . $end;
} else if (isset($_GET['date']) && $_GET['date'] === 'yesterday') {
    $date = date('Y-m-d', strtotime('-1 day'));
    $date_label = $date;
} else if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $date_label = $date;
} else {
    $date = date('Y-m-d');
    $date_label = $date;
}

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    outputPdfError('Error connecting to database: ' . $conn->connect_error, $date_label);
}

if ($use_range) {
    $sql = "SELECT p.product_name, SUM(ol.quantity) AS quantity_sold, SUM(ol.total) AS total_sales
            FROM orders o
            JOIN order_list ol ON o.id = ol.order_id
            JOIN products p ON ol.product_id = p.id
            WHERE DATE(o.date_created) BETWEEN ? AND ? AND o.status = 3 AND o.paid = 1
            GROUP BY ol.product_id, p.product_name
            ORDER BY total_sales DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        outputPdfError('Database query error: ' . $conn->error, $date_label);
    }
    $stmt->bind_param('ss', $start, $end);
} else {
    $sql = "SELECT p.product_name, SUM(ol.quantity) AS quantity_sold, SUM(ol.total) AS total_sales
            FROM orders o
            JOIN order_list ol ON o.id = ol.order_id
            JOIN products p ON ol.product_id = p.id
            WHERE DATE(o.date_created) = ? AND o.status = 3 AND o.paid = 1
            GROUP BY ol.product_id, p.product_name
            ORDER BY total_sales DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        outputPdfError('Database query error: ' . $conn->error, $date_label);
    }
    $stmt->bind_param('s', $date);
}
if (!$stmt->execute()) {
    outputPdfError('Query execution error: ' . $stmt->error, $date_label);
}
$result = $stmt->get_result();

// Debug mode for troubleshooting
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    $rows = $result->num_rows;
    header('Content-Type: text/plain');
    echo "SQL: $sql\n";
    echo $use_range ? "Start: $start\nEnd: $end\n" : "Date: $date\n";
    echo "Rows found: $rows\n";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
    exit;
}

// Calculate overall totals
$overall_quantity = 0;
$overall_sales = 0.00;
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
    $overall_quantity += $row['quantity_sold'];
    $overall_sales += $row['total_sales'];
}
$stmt->close();
$conn->close();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Paid Sales Report for ' . $date_label, 0, 1, 'C');
$pdf->Ln(5);

// Overall Totals
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 10, 'Overall Totals', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 8, 'Total Quantity Sold:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, $overall_quantity, 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 8, 'Total Sales:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'PHP ' . number_format($overall_sales, 2), 0, 1);
$pdf->Ln(5);

// Product Breakdown Table
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 10, 'Product Breakdown', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Product Name', 1);
$pdf->Cell(40, 10, 'Quantity Sold', 1, 0, 'C');
$pdf->Cell(60, 10, 'Total Sales (PHP)', 1, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
if (count($products) > 0) {
    foreach ($products as $row) {
        $pdf->Cell(90, 10, $row['product_name'], 1);
        $pdf->Cell(40, 10, $row['quantity_sold'], 1, 0, 'C');
        $pdf->Cell(60, 10, 'PHP ' . number_format($row['total_sales'], 2), 1, 0, 'R');
        $pdf->Ln();
    }
} else {
    $pdf->Cell(190, 10, 'No paid products found for this date.', 1, 1, 'C');
}
$pdf->Output('D', 'sales_report_' . str_replace(' ', '_', $date_label) . '.pdf');
exit; 