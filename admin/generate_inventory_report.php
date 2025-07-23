<?php
require_once('../config.php');
require_once('../classes/fpdf/fpdf.php');

// Get date parameter
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Create PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Daily Inventory Report', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Date: ' . date('F j, Y', strtotime($date)), 0, 1, 'C');
$pdf->Ln(10);

// Get inventory data for the date
$inventory_data = $conn->query("
    SELECT 
        i.*,
        p.product_name,
        c.category as category_name,
        sc.sub_category as sub_category_name
    FROM inventory i
    JOIN products p ON i.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
    ORDER BY p.product_name ASC
");

// Summary section
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Inventory Summary', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);

$total_products = $inventory_data->num_rows;
$out_of_stock = 0;
$low_stock = 0;
$in_stock = 0;

// Reset pointer to count
$inventory_data->data_seek(0);
while($row = $inventory_data->fetch_assoc()) {
    if($row['quantity'] <= 0) {
        $out_of_stock++;
    } elseif($row['quantity'] <= 5) {
        $low_stock++;
    } else {
        $in_stock++;
    }
}

$pdf->Cell(50, 6, 'Total Products:', 0, 0);
$pdf->Cell(30, 6, $total_products, 0, 1);
$pdf->Cell(50, 6, 'Out of Stock:', 0, 0);
$pdf->Cell(30, 6, $out_of_stock, 0, 1);
$pdf->Cell(50, 6, 'Low Stock:', 0, 0);
$pdf->Cell(30, 6, $low_stock, 0, 1);
$pdf->Cell(50, 6, 'In Stock:', 0, 0);
$pdf->Cell(30, 6, $in_stock, 0, 1);
$pdf->Ln(10);

// Detailed inventory table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Detailed Inventory List', 0, 1, 'L');
$pdf->Ln(5);

// Table headers
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 8, '#', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Category', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Price', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Stock', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Size', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Unit', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Status', 1, 1, 'C', true);

// Reset pointer for data
$inventory_data->data_seek(0);
$counter = 1;

$pdf->SetFont('Arial', '', 9);
while($row = $inventory_data->fetch_assoc()) {
    $status = '';
    if($row['quantity'] <= 0) {
        $status = 'Out of Stock';
    } elseif($row['quantity'] <= 5) {
        $status = 'Low Stock';
    } else {
        $status = 'In Stock';
    }
    
    $pdf->Cell(10, 8, $counter++, 1, 0, 'C');
    $pdf->Cell(60, 8, $row['product_name'], 1, 0, 'L');
    $pdf->Cell(30, 8, $row['category_name'] ?: 'N/A', 1, 0, 'C');
    $pdf->Cell(20, 8, '₱' . number_format($row['price']), 1, 0, 'R');
    $pdf->Cell(15, 8, $row['quantity'], 1, 0, 'C');
    $pdf->Cell(20, 8, $row['size'] ?: 'N/A', 1, 0, 'C');
    $pdf->Cell(20, 8, $row['unit'] ?: 'N/A', 1, 0, 'C');
    $pdf->Cell(25, 8, $status, 1, 1, 'C');
}

// Footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 5, 'Generated on: ' . date('F j, Y g:i A'), 0, 1, 'L');

// Output PDF
$pdf->Output('D', 'inventory_report_' . $date . '.pdf');
?>
