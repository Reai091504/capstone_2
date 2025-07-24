<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mail->SMTPDebug = SMTP_DEBUG;                    // Enable verbose debug output
            $this->mail->isSMTP();                                 // Send using SMTP
            $this->mail->Host       = SMTP_HOST;                   // Set the SMTP server to send through
            $this->mail->SMTPAuth   = SMTP_AUTH;                   // Enable SMTP authentication
            $this->mail->Username   = SMTP_USERNAME;               // SMTP username
            $this->mail->Password   = SMTP_PASSWORD;               // SMTP password
            $this->mail->SMTPSecure = SMTP_SECURE;                 // Enable TLS encryption
            $this->mail->Port       = SMTP_PORT;                   // TCP port to connect to
            
            // Additional settings for reliability
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Default sender
            $this->mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            
            // Set default character set
            $this->mail->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("Mailer initialization error: " . $e->getMessage());
            throw new Exception("Failed to initialize mailer: " . $e->getMessage());
        }
    }
    
    public function sendOTP($email, $otp) {
        try {
            // Clear any previous recipients
            $this->mail->clearAddresses();
            
            // Add recipient
            $this->mail->addAddress($email);
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Email Verification - Oyee Pet Shop';
            $this->mail->Body    = $this->getEmailTemplate($otp);
            $this->mail->AltBody = "Your OTP for email verification is: {$otp}. This OTP will expire in 10 minutes.";
            
            // Send email
            if(!$this->mail->send()) {
                error_log("Failed to send OTP to {$email}. Error: " . $this->mail->ErrorInfo);
                return false;
            }
            
            error_log("OTP sent successfully to: " . $email);
            return true;
            
        } catch (Exception $e) {
            error_log("Failed to send OTP to {$email}. Error: " . $e->getMessage());
            return false;
        }
    }
    
    private function getEmailTemplate($otp) {
        return "
            <html>
            <head>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .container { 
                        max-width: 600px; 
                        margin: 0 auto; 
                        padding: 20px;
                        background-color: #ffffff;
                    }
                    .header {
                        background-color: #007bff;
                        color: white;
                        padding: 20px;
                        text-align: center;
                        border-radius: 5px 5px 0 0;
                    }
                    .content {
                        padding: 20px;
                        background-color: #f8f9fa;
                        border-radius: 0 0 5px 5px;
                    }
                    .otp-code { 
                        font-size: 32px; 
                        color: #007bff; 
                        text-align: center;
                        padding: 20px;
                        background: #ffffff;
                        border-radius: 5px;
                        margin: 20px 0;
                        border: 2px dashed #007bff;
                    }
                    .footer { 
                        margin-top: 30px;
                        font-size: 12px;
                        color: #6c757d;
                        text-align: center;
                        border-top: 1px solid #dee2e6;
                        padding-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Email Verification</h2>
                    </div>
                    <div class='content'>
                        <p>Thank you for registering with Oyee Pet Shop. Please use the following OTP to verify your email address:</p>
                        <div class='otp-code'>{$otp}</div>
                        <p>This OTP will expire in 10 minutes.</p>
                        <p>If you didn't request this verification, please ignore this email.</p>
                        <div class='footer'>
                            <p>This is an automated message, please do not reply to this email.</p>
                            <p>&copy; " . date('Y') . " Oyee Pet Shop. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";
    }
} 