<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paymongo.com/v1/links",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'data' => [
            'attributes' => [
                'amount' => 200000,
                'description' => 'renato',
                'remarks' => 'uiouio'
            ]
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Basic c2tfdGVzdF94dnBXQUxqbXBTVURtU3NjRmo0MmltaUY6",
        "content-type: application/json"
    ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) { 
    echo "cURL Error #:" . $err;
} else {
    $decoded = json_decode($response, true);

    if (isset($decoded['data']['attributes']['checkout_url'])) {
        $checkoutUrl = $decoded['data']['attributes']['checkout_url'];
        header("Location: $checkoutUrl");
        exit();
    } else {
        echo "Failed to get checkout URL from response.";
    }
}
?>

    