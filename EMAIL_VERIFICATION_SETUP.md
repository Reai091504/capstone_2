# Email Verification System Setup Guide

## Overview
This guide will help you set up the email verification system for the Pet Shop application. The system uses PHPMailer with SMTP to send OTP (One-Time Password) emails for user registration verification.

## Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB database
- SMTP email account (Gmail recommended for testing)
- PHPMailer already installed in vendor folder

## Step 1: Database Setup

Run the following SQL to create the required table:

```sql
-- Create email_otps table for storing OTPs
CREATE TABLE IF NOT EXISTS `email_otps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `type` enum('verification','reset') NOT NULL DEFAULT 'verification',
  `expiry` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email_type_index` (`email`, `type`),
  KEY `expiry_index` (`expiry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Step 2: Email Configuration

Edit `config/mail_config.php` and update the following settings:

```php
// SMTP Settings
define('SMTP_HOST', 'smtp.gmail.com');           // Your SMTP server
define('SMTP_PORT', 587);                        // SMTP port
define('SMTP_SECURE', 'tls');                    // Encryption method
define('SMTP_AUTH', true);                       // Enable authentication
define('SMTP_USERNAME', 'bxaiglenn@gmail.com'); // Your email
define('SMTP_PASSWORD', 'owqkpodtcohiohhv');    // Your password/app password
define('SMTP_FROM_EMAIL', 'bxaiglenn@gmail.com'); // From email
define('SMTP_FROM_NAME', 'Oyee Pet Shop');       // From name
```

### Gmail Setup (Recommended for testing):
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a new app password for "Mail"
   - Use this password in `SMTP_PASSWORD`

## Step 3: Test the System

1. Update the test email in `test_email_verification.php`:
   ```php
   $test_email = "bxaiglenn@gmail.com";
   ```

2. Run the test script in your browser:
   ```
   http://localhost/pet_shop/test_email_verification.php
   ```

3. Check that all tests pass (green checkmarks)

## Step 4: Integration with Registration

The system is already integrated with the registration process:

1. **Registration Flow:**
   - User fills registration form
   - System generates OTP and sends email
   - User receives email with OTP
   - User enters OTP in verification modal
   - Account is created upon successful verification

2. **Resend OTP:**
   - Users can request new OTP if needed
   - 60-second cooldown between resend requests
   - OTP expires after 10 minutes

## Step 5: Features

### Security Features:
- OTPs are stored securely in database
- OTPs expire after 10 minutes
- OTPs can only be used once
- Rate limiting on resend requests
- Input validation and sanitization

### User Experience:
- Real-time countdown timer
- Auto-submit when 6 digits entered
- Clear error messages
- Responsive design
- Email templates with branding

### Email Templates:
- Professional HTML email templates
- Mobile-responsive design
- Branded with Pet Shop colors
- Clear instructions and warnings

## Step 6: Troubleshooting

### Common Issues:

1. **"Failed to send OTP" error:**
   - Check SMTP credentials in `config/mail_config.php`
   - Verify Gmail app password is correct
   - Check if 2FA is enabled on Gmail account

2. **"Invalid OTP" error:**
   - Check if email_otps table exists
   - Verify database connection
   - Check server timezone settings

3. **Email not received:**
   - Check spam/junk folder
   - Verify email address is correct
   - Check SMTP debug logs

### Debug Mode:
To enable debug mode, change in `config/mail_config.php`:
```php
define('SMTP_DEBUG', 2);  // Enable verbose debug output
```

## Step 7: Maintenance

### Cleanup Expired OTPs:
The system automatically cleans up expired OTPs, but you can also run manual cleanup:

```php
$emailService = new EmailService($conn);
$emailService->cleanupExpiredOTPs();
```

### Database Maintenance:
Regularly check the email_otps table size and clean up old records:

```sql
-- Delete OTPs older than 1 day
DELETE FROM email_otps WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
```

## Files Created/Modified:

### New Files:
- `config/mail_config.php` - Email configuration
- `classes/EmailService.php` - Email service class
- `database/email_otps_table.sql` - Database schema
- `test_email_verification.php` - Test script
- `EMAIL_VERIFICATION_SETUP.md` - This guide

### Modified Files:
- `classes/Master.php` - Updated registration and verification functions
- `customer/verify_email.php` - Enhanced verification modal

## Support

If you encounter any issues:
1. Check the error logs in your web server
2. Run the test script to identify specific problems
3. Verify all configuration settings
4. Test with a different email provider if needed

## Security Notes

- Never commit email credentials to version control
- Use environment variables for production credentials
- Regularly update PHPMailer to latest version
- Monitor for suspicious OTP requests
- Implement rate limiting for production use 