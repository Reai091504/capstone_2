<?php
// Test direct access to PDF generation
$_GET['date'] = date('Y-m-d');
include 'admin/generate_daily_orders_pdf.php';
?>
