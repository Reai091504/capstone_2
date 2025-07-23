<?php
// Test email configuration
$to = "bxaiglenn@gmail.com"; // Replace with your email
$subject = "Test Email from Pet Shop";
$message = "This is a test email to verify that the email system is working correctly.\n\n";
$message .= "If you receive this email, the configuration is successful.\n\n";
$message .= "Best regards,\nPet Shop Team";

$headers = "From: noreply@petshop.com";

if(mail($to, $subject, $message, $headers)) {
    echo "Test email sent successfully! Please check your inbox.";
} else {
    echo "Failed to send test email. Please check your configuration.";
}
?> 