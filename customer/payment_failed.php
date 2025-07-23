<?php 
require_once('../config.php');
require_once('../classes/Master.php');
require_once('../inc/sess_auth.php');

// Check if payment failed
$paymentId = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';

// If redirected from PayMongo, redirect to checkout page
if (isset($_GET['from_paymongo']) && $_GET['from_paymongo'] == 1) {
    header('Location: /checkout.php?payment=failed');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Pet Shop</title>
    <link rel="stylesheet" href="../plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .failed-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }
        .failed-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="failed-card">
        <i class="fas fa-times-circle failed-icon"></i>
        <h2 class="text-danger mb-3">Payment Failed</h2>
        <p class="text-muted mb-4">Unfortunately, your payment could not be processed. Please try again or use a different payment method.</p>
        
        <div class="mb-4">
            <?php if($paymentId): ?>
                <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($paymentId) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="d-grid gap-2">
            <a href="./checkout.php" class="btn btn-primary">Try Again</a>
            <a href="./" class="btn btn-outline-secondary">Back to Home</a>
        </div>
    </div>
</body>
</html>
