<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/DBConnection.php';
$db = new DBConnection;
$conn = $db->conn;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id) {
    echo json_encode(['status' => 'failed', 'html' => '<div class="alert alert-danger">Invalid order ID.</div>', 'cancellable' => false]);
    exit;
}

$qry = $conn->query("SELECT o.*, c.firstname, c.lastname FROM orders o INNER JOIN clients c ON c.id = o.client_id WHERE o.id = '$id'");
if($qry && $qry->num_rows > 0) {
    $row = $qry->fetch_assoc();
    $status = (int)$row['status'];
    $cancellable = ($status === 0);
    $html = '<div><b>Order Date:</b> '.date('Y-m-d H:i', strtotime($row['date_created'])).'</div>';
    $html .= '<div><b>Delivery Address:</b> '.htmlspecialchars($row['delivery_address']).'</div>';
    $html .= '<div><b>Payment Method:</b> '.htmlspecialchars($row['payment_method']).'</div>';
    $html .= '<div><b>Status:</b> ';
    if($status == 0) $html .= '<span class="badge badge-light text-dark">Pending</span>';
    elseif($status == 1) $html .= '<span class="badge badge-primary">Packed</span>';
    elseif($status == 2) $html .= '<span class="badge badge-warning">Out for Delivery</span>';
    elseif($status == 3) $html .= '<span class="badge badge-success">Delivered</span>';
    else $html .= '<span class="badge badge-danger">Cancelled</span>';
    $html .= '</div>';
    $html .= '<hr/>';
    $html .= '<h6>Order Items</h6>';
    $html .= '<table class="table table-bordered table-sm"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>';
    $items = $conn->query("SELECT o.*, p.product_name FROM order_list o INNER JOIN products p ON o.product_id = p.id WHERE o.order_id = '$id'");
    while($item = $items->fetch_assoc()) {
        $subtotal = $item['price'] * $item['quantity'];
        $html .= '<tr>';
        $html .= '<td>'.htmlspecialchars($item['product_name']).'</td>';
        $html .= '<td>'.(int)$item['quantity'].'</td>';
        $html .= '<td>₱'.number_format($item['price'],2).'</td>';
        $html .= '<td>₱'.number_format($subtotal,2).'</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
    $html .= '<div class="text-right"><b>Total: ₱'.number_format($row['amount'],2).'</b></div>';
    echo json_encode(['status' => 'success', 'html' => $html, 'cancellable' => $cancellable]);
} else {
    echo json_encode(['status' => 'failed', 'html' => '<div class="alert alert-danger">Order not found.</div>', 'cancellable' => false]);
} 