<?php
// Email Configuration for PHPMailer
// Update these settings with your actual email credentials

// SMTP Settings
define('SMTP_HOST', 'smtp.gmail.com');           // SMTP server (e.g., smtp.gmail.com)
define('SMTP_PORT', 587);                        // SMTP port (587 for TLS, 465 for SSL)
define('SMTP_SECURE', 'tls');                    // Encryption method (tls or ssl)
define('SMTP_AUTH', true);                       // Enable SMTP authentication
define('SMTP_USERNAME', 'bxaiglenn@gmail.com'); // Your email address
define('SMTP_PASSWORD', 'owqkpodtcohiohhv');    // Your email password or app password
define('SMTP_FROM_EMAIL', 'bxaiglenn@gmail.com'); // From email address
define('SMTP_FROM_NAME', 'Oyee Pet Shop');       // From name
define('SMTP_DEBUG', 0);                         // Debug level (0 = off, 1 = client messages, 2 = client and server messages)

// Email Templates
define('EMAIL_SUBJECT_VERIFICATION', 'Email Verification - Oyee Pet Shop');
define('EMAIL_SUBJECT_RESET', 'Password Reset - Oyee Pet Shop');

// OTP Settings
define('OTP_EXPIRY_MINUTES', 10);                // OTP expiry time in minutes
define('OTP_LENGTH', 6);                         // OTP length (6 digits)

// For development/testing - set to true to log emails instead of sending
define('EMAIL_LOG_MODE', false);
define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email.log');
