<?php
require_once '../config.php';
require_once '../classes/DBConnection.php';

$conn = (new DBConnection())->conn;
$token = isset($_GET['token']) ? $conn->real_escape_string($_GET['token']) : '';

$message = '';
$show_form = false;
if ($token) {
    $qry = $conn->query("SELECT * FROM clients WHERE email_verification_token = '{$token}' AND pending_email IS NOT NULL AND pending_email != ''");
    if ($qry && $qry->num_rows > 0) {
        $user = $qry->fetch_assoc();
        $update = $conn->query("UPDATE clients SET email = pending_email, pending_email = NULL, email_verification_token = NULL WHERE id = {$user['id']}");
        if ($update) {
            $message = '<div class="alert alert-success mt-3">Your new email has been verified and updated successfully. You can now use it to log in.</div>';
        } else {
            $message = '<div class="alert alert-danger mt-3">Failed to update your email. Please try again later.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger mt-3">Invalid or expired verification link, or email already verified.</div>';
    }
} else {
    // No token provided, show OTP input form
    $message = '';
    $show_form = true;
}
?>
<style>
    #uni_modal .modal-content {
        border: 2px solid #0d6efd;
        border-radius: 10px;
        background-color: #fffae6;
        color: #333;
        padding: 20px;
        max-width: 400px;
        margin: auto;
    }
    #uni_modal .modal-header {
        background-color: #0d6efd;
        color: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 15px 20px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
    }
    #uni_modal .modal-body {
        padding: 20px;
    }
    #uni_modal .form-control {
        border-radius: 20px;
        border: 1px solid #0d6efd;
        padding: 10px 15px;
        font-size: 1rem;
    }
    #uni_modal .btn-primary {
        background-color: #0d6efd;
        border: none;
        border-radius: 20px;
        padding: 10px 25px;
        font-size: 1rem;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    #uni_modal .btn-primary:hover {
        background-color: #084298;
    }
    #uni_modal .alert {
        font-size: 0.9rem;
        margin-top: 10px;
    }
    #otp-feedback {
        margin-top: 10px;
        font-size: 0.9rem;
        color: #dc3545;
        display: none;
    }
</style>

<div id="uni_modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
    <div class="modal-content">
        <div class="modal-header" id="modal-title">
            Email Verification
        </div>
        <div class="modal-body" id="modal-desc">
            <?php echo $message; ?>
            <?php if ($show_form): ?>
            <form id="otp-verification-form" novalidate>
                <label for="otp" class="form-label">Enter OTP sent to your email</label>
                <input type="text" id="otp" name="otp" class="form-control" required maxlength="6" pattern="\d{6}" />
                <div id="otp-feedback" role="alert"></div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Verify</button>
                    <button type="button" class="btn btn-secondary" onclick="closeUniModal()">Cancel</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function closeUniModal() {
        // Close the modal - assuming uni_modal is used elsewhere
        $('#uni_modal').modal('hide');
    }

    $(function () {
        // Remove preloader div on page load if present
        $('#preloader').remove();

        $('#otp-verification-form').on('submit', function (e) {
            e.preventDefault();
            var otpInput = $('#otp');
            var otp = otpInput.val().trim();
            var feedback = $('#otp-feedback');
            feedback.hide().text('');

            if (!otp) {
                feedback.text('Please enter the OTP.').show();
                otpInput.focus();
                return;
            }
            if (!/^\d{6}$/.test(otp)) {
                feedback.text('OTP must be a 6-digit number.').show();
                otpInput.focus();
                return;
            }

            $.ajax({
                url: '../classes/Master.php?f=verify_email',
                method: 'POST',
                data: { otp: otp },
                dataType: 'json',
                beforeSend: function () {
                    // Remove preloader div before AJAX call
                    $('#preloader').remove();
                    if(typeof end_loader === 'function') end_loader();
                },
                success: function (resp) {
                    if (typeof end_loader === 'function') end_loader();
                    if (resp.status === 'success') {
                        alert('Email verified successfully! You can now log in.');
                        closeUniModal();
                        window.location.href = '../login.php';
                    } else {
                        feedback.text(resp.msg || 'Verification failed. Please try again.').show();
                    }
                },
                error: function () {
                    if(typeof end_loader === 'function') end_loader();
                    feedback.text('An error occurred. Please try again.').show();
                }
            });
        });
    });
</script>
