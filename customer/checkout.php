<?php 
$total = 0;
$qry = $conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid FROM `cart` c 
    INNER JOIN `inventory` i ON i.id=c.inventory_id 
    INNER JOIN products p ON p.id = i.product_id 
    WHERE c.client_id = ".$_settings->userdata('id'));
while($row = $qry->fetch_assoc()):
    $subtotal = $row['price'] * $row['quantity'];
    $total += round($subtotal, 2);
endwhile;

$store_lat = 7.083524;  
$store_lng = 125.6032235; 

// Parse default delivery address into components for pre-filling fields
$default_address = $_settings->userdata('default_delivery_address');
$address_parts = array_map('trim', explode(',', $default_address));
$street_address = isset($address_parts[0]) ? $address_parts[0] : '';
$barangay = isset($address_parts[1]) ? $address_parts[1] : '';
$city = isset($address_parts[2]) ? $address_parts[2] : '';
$province = isset($address_parts[3]) ? $address_parts[3] : '';
$region = isset($address_parts[4]) ? $address_parts[4] : '';

// Create PayMongo checkout session
$amountCentavos = $total * 100; // PayMongo uses centavos (initial value)
$customerName = $_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname');
$remarks = "Petshop Order";

// Get current domain for redirect URLs
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
             $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $protocol . $_SERVER['HTTP_HOST'];

// Ensure the paths are correct relative to your domain
// The order ID will be appended dynamically in JavaScript after order placement
$successUrl = $domain . '/success_payment.php?id=';
$failedUrl = $domain . '/fail_payment.php?id=';

$curl = curl_init();
$line_items = array();
$qry = $conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid FROM `cart` c 
    INNER JOIN `inventory` i ON i.id=c.inventory_id 
    INNER JOIN products p ON p.id = i.product_id 
    WHERE c.client_id = ".$_settings->userdata('id'));
while($row = $qry->fetch_assoc()){
    $line_items[] = array(
        'name' => $row['product_name'],
        'quantity' => (int)$row['quantity'],
        'amount' => intval($row['price'] * 100),
        'currency' => 'PHP'
    );
}

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'data' => [
            'attributes' => [
                'send_email_receipt' => false,
                'show_description' => true,
                'show_line_items' => true,
                'line_items' => $line_items,
                'payment_method_types' => ['card', 'gcash', 'paymaya'],
                'success_url' => $successUrl,
                'cancel_url' => $failedUrl,
                'description' => $remarks,
                'billing' => [
                    'name' => $customerName,
                    'email' => $_settings->userdata('email'),
                    'phone' => $_settings->userdata('contact')
                ]
            ]
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Basic c2tfdGVzdF81THIxSkNmeVVaY2FyUDNWMkNmY2NBQmk6",
        "content-type: application/json"
    ],
));

$response = curl_exec($curl);
$err = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$checkoutUrl = '';
$paymongoError = '';

if ($err) {
    $paymongoError = "cURL Error: " . $err;
    $checkoutUrl = '#';
} else {
    $decoded = json_decode($response, true);
    file_put_contents('paymongo_debug.log', date('Y-m-d H:i:s') . " Response: " . $response . PHP_EOL, FILE_APPEND);

    if (($httpCode === 200 || $httpCode === 201)) {
        // Check for both possible response formats
        if (isset($decoded['data']['attributes']['checkout_url'])) {
            $checkoutUrl = $decoded['data']['attributes']['checkout_url'];
        } elseif (isset($decoded['data']['attributes']['url'])) {
            $checkoutUrl = $decoded['data']['attributes']['url'];
        } else {
            $paymongoError = "PayMongo API Error: No checkout URL found in response";
            file_put_contents('paymongo_debug.log', date('Y-m-d H:i:s') . " Error: " . $paymongoError . PHP_EOL, FILE_APPEND);
            $checkoutUrl = '#';
        }
    } else {
        $errorDetail = isset($decoded['errors'][0]['detail']) ? $decoded['errors'][0]['detail'] : 
                      (isset($decoded['error']['message']) ? $decoded['error']['message'] : 'Unknown error');
        $paymongoError = "PayMongo API Error: " . $errorDetail . " | HTTP Code: " . $httpCode . " | Full Response: " . $response;
        file_put_contents('paymongo_debug.log', date('Y-m-d H:i:s') . " Error: " . $paymongoError . PHP_EOL, FILE_APPEND);
        $checkoutUrl = '#';
    }
}
?>

<section class="py-5">
    <div class="container" style="margin-top: 100px; max-width: 1250px;">
        <div class="card rounded-4 shadow-lg border-0" style="background: rgba(255,255,255,0.97); box-shadow: 0 8px 32px rgba(60,141,188,0.12);">
            <div class="card-body p-5">
                <div class="d-flex justify-content-center align-items-center mb-3" style="position: relative;">
                    <a href="?p=cart" onclick="history.replaceState(null, null, '?p=cart');" class="btn btn-flat btn-secondary" style="position: absolute; left: 0;"><i class="fa fa-arrow-left"></i> Back to Cart</a>
                    <h3 class="text-center mb-0" style="color: #0d6efd;"><b>Checkout</b></h3>
                </div>
                <hr class="border-primary mb-4">
                <form action="" id="place_order">
                    <input type="hidden" name="amount" id="order_amount" value="<?php echo $total ?>">
                    <input type="hidden" name="delivery_fee" id="delivery_fee" value="0">
                    <input type="hidden" name="payment_method" value="cod">
                    <input type="hidden" name="paid" value="0">
                    <div class="row row-col-1 justify-content-center">
                        <div class="col-6">
                            <div class="form-group col mb-4">
                                <h4 class="text-muted mb-3">Customer Information</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div style="display: flex; justify-content: space-between;">
                                            <div style="width: 48%;">
                                                <p><strong>Name:</strong> <?php echo $customerName ?></p>
                                                <p><strong>Email:</strong> <?php echo $_settings->userdata('email') ?></p>
                                            </div>
                                            <div style="width: 48%;">
                                                <p><strong>Contact:</strong> <?php echo $_settings->userdata('contact') ?></p>
                                                <p><strong>Date Registered:</strong> <?php echo date("M d, Y", strtotime($_settings->userdata('date_created'))) ?></p>
                                            </div>
                                        </div>
                                        <hr class="my-3">
                                        <div>
                                            <label class="control-label"><strong>Delivery Address:</strong></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-2" id="street_address" placeholder="Street Address / Building / Unit" value="<?php echo htmlspecialchars($street_address) ?>">
                                                <input type="text" class="form-control mb-2" id="barangay" placeholder="Barangay" value="<?php echo htmlspecialchars($barangay) ?>">
                                                <input type="text" class="form-control mb-2" id="city" placeholder="City / Municipality" value="<?php echo htmlspecialchars($city) ?>">
                                                <input type="text" class="form-control mb-2" id="province" placeholder="Province" value="<?php echo htmlspecialchars($province) ?>">
                                                <input type="text" class="form-control mb-2" id="region" placeholder="Region" value="<?php echo htmlspecialchars($region) ?>">
                                                <input type="hidden" name="delivery_address" id="delivery_address" value="">
                                            </div>
                                        </div>
                                        <hr class="my-3">
                                        <div>
                                            <h5 class="text-muted mb-3">Ordered Items</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $qry = $conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid FROM `cart` c 
                                                            INNER JOIN `inventory` i ON i.id=c.inventory_id 
                                                            INNER JOIN products p ON p.id = i.product_id 
                                                            WHERE c.client_id = ".$_settings->userdata('id'));
                                                        while($row = $qry->fetch_assoc()):
                                                            $subtotal = $row['price'] * $row['quantity'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row['product_name'] ?></td>
                                                            <td>₱<?php echo number_format($row['price'], 2) ?></td>
                                                            <td><?php echo $row['quantity'] ?></td>
                                                            <td>₱<?php echo number_format($subtotal, 2) ?></td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between">
                                    <span><h5>Subtotal:</h5></span>
                                    <span><h5>₱<?php echo number_format($total, 2, '.', ',') ?></h5></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><h5>Delivery Fee:</h5></span>
                                    <span><h5 id="delivery_fee_display">₱0.00</h5></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span><h4><b>Total:</b></span>
                                    <span><h4 id="total_display">₱<?php echo number_format($total, 2, '.', ',') ?></h4></span>
                                </div>
                                <?php if ($total < 1000): ?>
                                    <div class="alert alert-warning mt-2" style="font-size: 0.9em;">
                                        <small><strong>Notice:</strong> ₱1,000 is the minimum order.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div class="col my-3">
                                <h4 class="text-muted">Payment Method</h4>
                                <div class="d-flex w-100 justify-content-between">
                                    <button type="submit" class="btn btn-flat btn-dark" id="cod-btn" <?php echo ($total < 1000) ? 'disabled' : '' ?>>Cash on Delivery</button>
                    <?php if ($checkoutUrl && $checkoutUrl !== '#' && $total >= 1000): ?>
                        <a href="<?php echo $checkoutUrl ?>" target="_blank" class="btn btn-success" id="paymongo-btn">Pay with PayMongo</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-success" id="paymongo-btn" disabled>
                            Pay with PayMongo (Unavailable)
                        </button>
                    <?php endif; ?>
                                </div>
                                <?php if ($paymongoError): ?>
                                    <div class="alert alert-warning mt-2" style="font-size: 0.9em;">
                                        <small><strong>PayMongo Error:</strong> <?php echo htmlspecialchars($paymongoError) ?></small>
                                    </div>
                                <?php endif; ?>
                                <?php if ($total < 1000): ?>
                                    <div class="alert alert-warning mt-2" style="font-size: 0.9em;">
                                        <small><strong>Notice:</strong> The minimum order is ₱1000.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.card.rounded-4 {
    border-radius: 1.5rem !important;
}
#place_order .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
}
#place_order label, #place_order h4, #place_order h5 {
    color: #3c8dbc;
    font-weight: 500;
}
#place_order button.btn-dark {
    background: #0d6efd;
    border: none;
    border-radius: 20px;
    color: #fff;
    transition: background 0.2s;
}
#place_order button.btn-dark:hover {
    background: #084298;
    color: #fff;
}
#place_order .btn-success {
    border-radius: 20px;
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPctAGakf94USb03diznTaApLt7ZwXcJ4"></script>

<script>
$(function(){
    var storeLat = <?php echo $store_lat ?>;
    var storeLng = <?php echo $store_lng ?>;
    var deliveryAddress = $('input[name="delivery_address"]').val();
    var totalAmount = parseFloat($('#order_amount').val());

    function calculateDistance(lat1, lng1, lat2, lng2) {
        function toRad(x) {
            return x * Math.PI / 180;
        }
        var R = 6371; // Radius of Earth in km
        var dLat = toRad(lat2 - lat1);
        var dLng = toRad(lng2 - lng1);
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d; // Distance in km
    }

    function geocodeAddress(address, callback) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK' && results[0]) {
                var location = results[0].geometry.location;
                callback(location.lat(), location.lng());
            } else {
                callback(null, null);
            }
        });
    }

    function updateDeliveryFeeAndTotal(distance) {
        var deliveryFee = 0;
        if (distance > 3) {
            deliveryFee = (distance - 3) * 10;
        }
        deliveryFee = Math.round(deliveryFee * 100) / 100; // round to 2 decimals
        $('#delivery_fee').val(deliveryFee);
        $('#delivery_fee_display').text('₱' + deliveryFee.toFixed(2));
        var newTotal = totalAmount + deliveryFee;
        $('#order_amount').val(newTotal);
        $('#total_display').text(newTotal.toFixed(2));
        
        // Update PayMongo button with new total
        updatePayMongoLink(newTotal);
    }

    function updatePayMongoLink(totalAmount) {
        // Disable button while updating
        $('#paymongo-btn').addClass('disabled').text('Updating...');
        
        // Make AJAX call to update PayMongo link with new amount
        $.ajax({
            url: 'classes/Master.php?f=update_paymongo_link',
            method: 'POST',
            data: {amount: totalAmount},
            dataType: 'json',
            success: function(response) {
                if(response.success && response.checkout_url) {
                    $('#paymongo-btn').attr('href', response.checkout_url)
                        .removeClass('disabled')
                        .text('Pay with PayMongo');
                } else {
                    $('#paymongo-btn').attr('href', '#')
                        .addClass('disabled')
                        .text('Pay with PayMongo (Error)');
                }
            },
            error: function() {
                $('#paymongo-btn').attr('href', '#')
                    .addClass('disabled')
                    .text('Pay with PayMongo (Error)');
            }
        });
    }

    function updateFullAddress() {
        var street = $('#street_address').val();
        var barangay = $('#barangay').val();
        var city = $('#city').val();
        var province = $('#province').val();
        var region = $('#region').val();
        var fullAddress = street;
        if(barangay) fullAddress += ', ' + barangay;
        if(city) fullAddress += ', ' + city;
        if(province) fullAddress += ', ' + province;
        if(region) fullAddress += ', ' + region;
        $('#delivery_address').val(fullAddress);
        return fullAddress;
    }

    function calculateAndUpdate() {
        var fullAddress = updateFullAddress();
        if (fullAddress) {
            geocodeAddress(fullAddress, function(lat, lng) {
                if (lat !== null && lng !== null) {
                    var distance = calculateDistance(storeLat, storeLng, lat, lng);
                    updateDeliveryFeeAndTotal(distance);
                } else {
                    // Could not geocode address, assume free delivery or handle error
                    updateDeliveryFeeAndTotal(0);
                }
            });
        } else {
            updateDeliveryFeeAndTotal(0);
        }
    }

    $('#street_address, #barangay, #city, #province, #region').on('change keyup', function() {
        calculateAndUpdate();
    });

    // Initial calculation on page load
    calculateAndUpdate();

    $('#place_order').submit(function(e){
        e.preventDefault();
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=place_order',
            method:'POST',
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err);
                alert_toast("An error occurred","error");
                end_loader();
            },
            success:function(resp){
                if(resp.status == 'success'){
                    alert_toast("Order successfully placed.","success");
                    setTimeout(function(){
                        location.replace('./')
                    },2000)
                } else {
                    console.log(resp);
                    alert_toast("An error occurred","error");
                    end_loader();
                }
            }
        })
    })
    
    // Add loading modal for PayMongo payment
    $('#paymongo-btn').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        if(url === '#' || $(this).hasClass('disabled')) return;

        // Open PayMongo checkout in new window first
        var paymongoWindow = window.open(url, 'PayMongo Payment', 'width=600,height=700');

        // Polling to detect if payment_success.php is loaded in new window
        var pollTimer = setInterval(function() {
            try {
                if(paymongoWindow.location.href.indexOf('payment_success.php') !== -1) {
                    clearInterval(pollTimer);
                    paymongoWindow.close();

                    // Place order in backend with paid=1 (paid)
                    var formData = $('#place_order').serializeArray();
                    formData.push({name: 'payment_method', value: 'paymongo'});
                    formData.push({name: 'paid', value: 1});

                    $.ajax({
                        url: 'classes/Master.php?f=place_order',
                        method: 'POST',
                        data: $.param(formData),
                        dataType: 'json',
                        success: function(resp) {
                            if(resp.status === 'success') {
                                alert_toast("Payment successful and order placed.", "success");
                                window.location.href = '/?p=my_account';
                            } else {
                                alert_toast("Failed to place order after payment.", "error");
                            }
                        },
                        error: function() {
                            alert_toast("An error occurred while placing order.", "error");
                        }
                    });
                }
            } catch(e) {
                // Cross-origin error expected until redirected to same origin
            }
            if(paymongoWindow.closed) {
                clearInterval(pollTimer);
                // If window closed without success, do not place order
                alert_toast("Payment window closed. Order not placed.", "warning");
            }
        }, 1000);
    });
    
    function placeOrderAfterPayment(){
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=place_order',
            method:'POST',
            data: $('#place_order').serialize(),
            dataType:"json",
            error: function(err){
                console.log(err);
                alert_toast("An error occurred","error");
                end_loader();
            },
            success: function(resp){
                if(resp.status == 'success'){
                    alert_toast("Order successfully placed.","success");
                    setTimeout(function(){
                        location.replace('./')
                    },2000);
                } else {
                    console.log(resp);
                    alert_toast("An error occurred","error");
                    end_loader();
                }
            }
        });
    }
})
</script>
