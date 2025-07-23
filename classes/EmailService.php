<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailService {
    private $mailer;
    private $db;
    
    public function __construct($db_connection = null) {
        $this->db = $db_connection;
        $this->initializeMailer();
    }
    
    /**
     * Initialize PHPMailer with SMTP settings
     */
    private function initializeMailer() {
        try {
            $this->mailer = new PHPMailer(true);
            
            // Server settings
            $this->mailer->SMTPDebug = SMTP_DEBUG;
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = SMTP_AUTH;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_SECURE;
            $this->mailer->Port = SMTP_PORT;
            
            // Additional settings for reliability
            $this->mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Default sender
            $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $this->mailer->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("EmailService initialization error: " . $e->getMessage());
            throw new Exception("Failed to initialize email service: " . $e->getMessage());
        }
    }
    
    /**
     * Generate a random OTP
     */
    public function generateOTP($length = OTP_LENGTH) {
        return str_pad(mt_rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
    
    /**
     * Store OTP in database with expiry
     */
    public function storeOTP($email, $otp, $type = 'verification') {
        try {
            $expiry = date('Y-m-d H:i:s', strtotime('+' . OTP_EXPIRY_MINUTES . ' minutes'));
            
            // Delete any existing OTP for this email
            $this->db->query("DELETE FROM email_otps WHERE email = '$email' AND type = '$type'");
            
            // Insert new OTP
            $sql = "INSERT INTO email_otps (email, otp, type, expiry, created_at) 
                    VALUES ('$email', '$otp', '$type', '$expiry', NOW())";
            
            $result = $this->db->query($sql);
            
            if (!$result) {
                error_log("Failed to store OTP: " . $this->db->error);
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error storing OTP: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify OTP from database
     */
    public function verifyOTP($email, $otp, $type = 'verification') {
        try {
            $current_time = date('Y-m-d H:i:s');
            $sql = "SELECT * FROM email_otps 
                    WHERE email = '$email' 
                    AND otp = '$otp' 
                    AND type = '$type' 
                    AND expiry > '$current_time' 
                    AND used = 0 
                    ORDER BY created_at DESC 
                    LIMIT 1";
            $result = $this->db->query($sql);
            if ($result && $result->num_rows > 0) {
                // Mark OTP as used
                $otp_id = $result->fetch_assoc()['id'];
                $this->db->query("UPDATE email_otps SET used = 1 WHERE id = $otp_id");
                return true;
            }
            // If OTP is wrong, delete it for this email/type (prevents storing failed attempts)
            $this->db->query("DELETE FROM email_otps WHERE email = '$email' AND otp = '$otp' AND type = '$type'");
            return false;
        } catch (Exception $e) {
            error_log("Error verifying OTP: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send OTP email
     */
    public function sendOTP($email, $otp, $type = 'verification') {
        try {
            // Clear any previous recipients
            $this->mailer->clearAddresses();
            
            // Add recipient
            $this->mailer->addAddress($email);
            
            // Set subject and content based on type
            if ($type === 'verification') {
                $this->mailer->Subject = EMAIL_SUBJECT_VERIFICATION;
                $this->mailer->Body = $this->getVerificationEmailTemplate($otp);
                $this->mailer->AltBody = "Your OTP for email verification is: {$otp}. This OTP will expire in " . OTP_EXPIRY_MINUTES . " minutes.";
            } else {
                $this->mailer->Subject = EMAIL_SUBJECT_RESET;
                $this->mailer->Body = $this->getResetEmailTemplate($otp);
                $this->mailer->AltBody = "Your OTP for password reset is: {$otp}. This OTP will expire in " . OTP_EXPIRY_MINUTES . " minutes.";
            }
            
            $this->mailer->isHTML(true);
            
            // Send email
            if (!$this->mailer->send()) {
                error_log("Failed to send OTP to {$email}. Error: " . $this->mailer->ErrorInfo);
                return false;
            }
            
            error_log("OTP sent successfully to: " . $email);
            return true;
            
        } catch (Exception $e) {
            error_log("Failed to send OTP to {$email}. Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get verification email HTML template
     */
    private function getVerificationEmailTemplate($otp) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Email Verification</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }
                .otp-code { background-color: #e9ecef; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; color: #0d6efd; border-radius: 5px; margin: 20px 0; letter-spacing: 5px; }
                .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
                .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Email Verification</h1>
                    <p>Oyee Pet Shop</p>
                </div>
                <div class='content'>
                    <p>Thank you for registering with Oyee Pet Shop!</p>
                    <p>Please use the following OTP to verify your email address:</p>
                    
                    <div class='otp-code'>{$otp}</div>
                    
                    <div class='warning'>
                        <strong>Important:</strong> This OTP will expire in " . OTP_EXPIRY_MINUTES . " minutes.
                    </div>
                    
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
    
    /**
     * Get password reset email HTML template
     */
    private function getResetEmailTemplate($otp) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }
                .otp-code { background-color: #e9ecef; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; color: #dc3545; border-radius: 5px; margin: 20px 0; letter-spacing: 5px; }
                .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
                .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Password Reset</h1>
                    <p>Oyee Pet Shop</p>
                </div>
                <div class='content'>
                    <p>You have requested to reset your password.</p>
                    <p>Please use the following OTP to complete the password reset process:</p>
                    
                    <div class='otp-code'>{$otp}</div>
                    
                    <div class='warning'>
                        <strong>Important:</strong> This OTP will expire in " . OTP_EXPIRY_MINUTES . " minutes.
                    </div>
                    
                    <p>If you didn't request this password reset, please ignore this email.</p>
                    
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
    
    /**
     * Clean up expired OTPs
     */
    public function cleanupExpiredOTPs() {
        try {
            $current_time = date('Y-m-d H:i:s');
            $sql = "DELETE FROM email_otps WHERE expiry < '$current_time'";
            $this->db->query($sql);
        } catch (Exception $e) {
            error_log("Error cleaning up expired OTPs: " . $e->getMessage());
        }
    }

    /**
     * Send email change verification link
     */
    public function sendVerificationLink($email, $token) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'Verify your new email address';
            $verificationUrl = base_url . "customer/verify_email.php?token=" . urlencode($token);
            $this->mailer->Body = $this->getEmailChangeVerificationTemplate($verificationUrl);
            $this->mailer->AltBody = "Please verify your new email address by clicking this link: $verificationUrl";
            $this->mailer->isHTML(true);
            if (!$this->mailer->send()) {
                error_log("Failed to send verification link to {$email}. Error: " . $this->mailer->ErrorInfo);
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("Failed to send verification link to {$email}. Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get email change verification HTML template
     */
    private function getEmailChangeVerificationTemplate($verificationUrl) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Verify New Email</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f8f9fa; padding: 30px; border-radius: 0 0 5px 5px; }
                .verify-link { display: inline-block; background: #0d6efd; color: #fff; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Verify Your New Email</h1>
                    <p>Oyee Pet Shop</p>
                </div>
                <div class='content'>
                    <p>You requested to change your email address for your Oyee Pet Shop account.</p>
                    <p>Please click the button below to verify your new email address:</p>
                    <a href='$verificationUrl' class='verify-link'>Verify Email</a>
                    <p>If you did not request this change, you can safely ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message, please do not reply to this email.</p>
                    <p>&copy; " . date('Y') . " Oyee Pet Shop. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?> 