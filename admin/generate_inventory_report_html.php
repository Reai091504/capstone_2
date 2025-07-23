<?php
require_once('../config.php');

// Get date parameter
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Get inventory data
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

// Check if query was successful
if (!$inventory_data) {
    die("Error getting inventory data: " . $conn->error);
}

// Calculate summary
$total_products = $inventory_data->num_rows;
$out_of_stock = 0;
$low_stock = 0;
$in_stock = 0;

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

// Reset pointer
$inventory_data->data_seek(0);

// Set headers for download
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="inventory_report_' . $date . '.html"');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Inventory Report - <?php echo date('F j, Y', strtotime($date)); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin-bottom: 30px; }
        .summary-table { width: 50%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table th, .summary-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .summary-table th { background-color: #f2f2f2; }
        .inventory-table { width: 100%; border-collapse: collapse; }
        .inventory-table th, .inventory-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .inventory-table th { background-color: #f2f2f2; }
        .out-of-stock { color: red; font-weight: bold; }
        .low-stock { color: orange; font-weight: bold; }
        .in-stock { color: green; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Inventory Report</h1>
        <h2><?php echo date('F j, Y', strtotime($date)); ?></h2>
    </div>

    <div class="summary">
        <h3>Inventory Summary</h3>
        <table class="summary-table">
            <tr>
                <th>Total Products</th>
                <td><?php echo $total_products; ?></td>
            </tr>
            <tr>
                <th>Out of Stock</th>
                <td><?php echo $out_of_stock; ?></td>
            </tr>
            <tr>
                <th>Low Stock (≤5)</th>
                <td><?php echo $low_stock; ?></td>
            </tr>
            <tr>
                <th>In Stock</th>
                <td><?php echo $in_stock; ?></td>
            </tr>
        </table>
    </div>

    <h3>Detailed Inventory List</h3>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Size</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $counter = 1;
            while($row = $inventory_data->fetch_assoc()): 
                $status = '';
                $status_class = '';
                if($row['quantity'] <= 0) {
                    $status = 'Out of Stock';
                    $status_class = 'out-of-stock';
                } elseif($row['quantity'] <= 5) {
                    $status = 'Low Stock';
                    $status_class = 'low-stock';
                } else {
                    $status = 'In Stock';
                    $status_class = 'in-stock';
                }
            ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['sub_category_name'] ?: 'N/A'); ?></td>
                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo htmlspecialchars($row['size'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['unit'] ?: 'N/A'); ?></td>
                <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: <?php echo date('F j, Y g:i A'); ?></p>
        <p>Pet Shop Inventory Management System</p>
    </div>

    <!-- Inventory Out List Section -->
    <div style="margin-top: 40px; page-break-before: always;">
        <h2>Inventory Out List (Products Sold on <?php echo date('F j, Y', strtotime($date)); ?>)</h2>
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Get inventory out data for the selected date
                $out_qry = $conn->query("
                    SELECT 
                        ol.product_id,
                        p.product_name,
                        ol.quantity,
                        ol.price,
                        (ol.quantity * ol.price) as total,
                        o.date_created as datetime
                    FROM order_list ol
                    JOIN orders o ON ol.order_id = o.id
                    JOIN products p ON ol.product_id = p.id
                    WHERE DATE(o.date_created) = '$date' 
                    AND o.paid = 1 
                    AND o.status != 4
                    ORDER BY o.date_created DESC, p.product_name ASC
                ");
                
                // Check if query was successful
                if ($out_qry === false) {
                    echo '<tr><td colspan="9" style="text-align: center; color: red;">Error fetching sold products: ' . $conn->error . '</td></tr>';
                } elseif ($out_qry->num_rows > 0) {
                    $counter = 1;
                    $total_sold = 0;
                    $total_quantity = 0;
                    while($out_row = $out_qry->fetch_assoc()):
                        $total_sold += $out_row['total'];
                        $total_quantity += $out_row['quantity'];
                ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $out_row['product_id']; ?></td>
                    <td><?php echo htmlspecialchars($out_row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($out_row['size'] ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($out_row['unit'] ?: 'N/A'); ?></td>
                    <td><?php echo $out_row['quantity']; ?></td>
                    <td>₱<?php echo number_format($out_row['price'], 2); ?></td>
                    <td>₱<?php echo number_format($out_row['total'], 2); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($out_row['datetime'])); ?></td>
                </tr>
                <?php 
                    endwhile;
                ?>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="5">TOTAL</td>
                    <td><?php echo $total_quantity; ?></td>
                    <td>-</td>
                    <td>₱<?php echo number_format($total_sold, 2); ?></td>
                    <td>-</td>
                </tr>
                <?php 
                } else { 
                ?>
                <tr>
                    <td colspan="9" style="text-align: center;">No products sold on this date</td>
                </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
exit;
?>