nsac<?php 
require_once('../config.php');
require_once('../classes/Master.php');
require_once('../inc/sess_auth.php');

$paymentId = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';

if(empty($paymentId) || empty($transactionId)) {
    header('Location: ./');
    exit;
}

if(!empty($transactionId)){
    $Master = new Master();
    // Assuming transaction_id corresponds to order_id, adjust if different
    $postData = ['id' => $transactionId];
    $_POST = $postData;
    $response = json_decode($Master->pay_order(), true);
    if($response['status'] !== 'success'){
        error_log("Failed to update order payment status for transaction ID: $transactionId");
    }
    // Update order with transaction ID
    $postData = ['order_id' => $transactionId, 'transaction_id' => $paymentId];
    $_POST = $postData;
    $response2 = json_decode($Master->update_order_transaction_id(), true);
    if($response2['status'] !== 'success'){
        error_log("Failed to update order transaction ID for order ID: $transactionId");
    }
}

$transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';

// Always redirect to order confirmation page after successful payment
if (!empty($transactionId)) {
    if(!empty($orderId)) {
        // Redirect to order confirmation page with success parameter and transaction id
        header('Location: /my_account.php?p=orders&order_id=' . $orderId . '&payment=success&transaction_id=' . urlencode($transactionId));
        exit;
    } else {
        // Fallback redirect
        header('Location: /my_account.php?p=orders&payment=success&transaction_id=' . urlencode($transactionId));
        exit;
    }
}

// Regular redirect for non-PayMongo payments
if(!empty($orderId)) {
    header('Location: /my_account.php?p=orders&order_id=' . $orderId . '&payment=success');
} else {
    header('Location: /my_account.php?p=orders&payment=success');
}
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Pet Shop</title>
    <link rel="stylesheet" href="../plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <i class="fas fa-check-circle success-icon"></i>
        <h2 class="text-success mb-3">Payment Successful!</h2>
        <p class="text-muted mb-4">Thank you for your purchase. Your payment has been processed successfully.</p>
        
        <div class="mb-4">
            <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($paymentId) ?></p>
            <?php if($orderId): ?>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderId) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="d-grid gap-2">
            <a href="./my_account.php" class="btn btn-primary">View My Orders</a>
            <a href="./" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
    </div>
</body>
</html>
