<section class="py-5">
    <div class="container" style="margin-top: 100px; max-width: 700px;">
        <div class="card rounded-4 shadow-lg border-0" style="background: rgba(255,255,255,0.97); box-shadow: 0 8px 32px rgba(60,141,188,0.12);">
            <div class="card-body p-5">
                <div class="w-100 justify-content-between d-flex align-items-center mb-3">
                    <h4 class="mb-0" style="color: #0d6efd;"><b>Change Password</b></h4>
                    <a href="./?p=my_account" class="btn btn btn-dark btn-flat"><div class="fa fa-angle-left"></div> Back to My Account</a>
                </div>
                <hr class="border-primary mb-4">
                <div class="d-flex justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="" id="change_password_form">
                            <input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
                            <div class="form-group" style="position:relative;">
                                <label for="password" class="control-label">New Password</label>
                                <input type="password" name="password" class="form-control form" id="change-password" value="" placeholder="(Enter new password)" required>
                                <span class="toggle-password" toggle="#change-password" style="position:absolute;top:38px;right:10px;cursor:pointer;"><i class="fa fa-eye-slash"></i></span>
                                <div class="password-strength"></div>
                                <div class="password-feedback"></div>
                            </div>
                            <div class="form-group" style="position:relative;">
                                <label for="confirm_password" class="control-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control form" id="change-confirm-password" value="" placeholder="(Re-enter your new password)" required>
                                <span class="toggle-password" toggle="#change-confirm-password" style="position:absolute;top:38px;right:10px;cursor:pointer;"><i class="fa fa-eye-slash"></i></span>
                            </div>
                            <div class="form-group d-flex align-items-center" id="otp-group" style="margin-bottom: 1rem;">
                                <input type="text" name="otp" class="form-control form mr-2" id="change-otp" value="" placeholder="Enter OTP" required style="max-width:180px; margin-right: 10px;">
                                <button type="button" class="btn btn-outline-primary btn-flat" id="send-otp-btn">Send OTP</button>
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <button class="btn btn-dark btn-flat">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
.card.rounded-4 {
    border-radius: 1.5rem !important;
}
#change_password_form .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
}
#change_password_form label {
    color: #3c8dbc;
    font-weight: 500;
}
#change_password_form button.btn-dark {
    background: #0d6efd;
    border: none;
    border-radius: 20px;
    color: #fff;
    transition: background 0.2s;
}
#change_password_form button.btn-dark:hover {
    background: #084298;
    color: #fff;
}
.password-strength {
    height: 4px;
    margin-top: 5px;
    border-radius: 2px;
    transition: all 0.3s ease;
}
.strength-weak {
    background: linear-gradient(to right, #dc3545 0%, #dc3545 20%, #e9ecef 20%, #e9ecef 100%);
}
.strength-medium {
    background: linear-gradient(to right, #ffc107 0%, #ffc107 60%, #e9ecef 60%, #e9ecef 100%);
}
.strength-strong {
    background: linear-gradient(to right, #28a745 0%, #28a745 80%, #e9ecef 80%, #e9ecef 100%);
}
.strength-very-strong {
    background: #28a745;
}
.password-feedback {
    font-size: 0.875rem;
    margin-top: 5px;
    color: #6c757d;
}
#send-otp-btn {
    background: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 4px 14px;
    font-weight: 500;
    font-size: 0.98rem;
    box-shadow: 0 2px 8px rgba(13,110,253,0.08);
    transition: background 0.2s, box-shadow 0.2s;
    outline: none;
}
#send-otp-btn:disabled {
    background: #6c757d;
    color: #fff;
    box-shadow: none;
    cursor: not-allowed;
}
#send-otp-btn .spinner-border {
    width: 1rem;
    height: 1rem;
    border-width: 2px;
    margin-right: 6px;
    vertical-align: middle;
}
#otp-group {
    margin-bottom: 1rem;
    padding-left: 0;
}
#otp-group input[type="text"] {
    margin-right: 10px;
}
</style>
<script>
$(function(){
    // Password strength checker (copied from registration.php)
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];
        if (password.length >= 8) {
            strength += 1;
        } else {
            feedback.push("Password should be at least 8 characters long");
        }
        if (/\d/.test(password)) {
            strength += 1;
        } else {
            feedback.push("Add numbers to make it stronger");
        }
        if (/[a-z]/.test(password)) {
            strength += 1;
        } else {
            feedback.push("Add lowercase letters");
        }
        if (/[A-Z]/.test(password)) {
            strength += 1;
        } else {
            feedback.push("Add uppercase letters");
        }
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 1;
        } else {
            feedback.push("Add special characters");
        }
        return {
            score: strength,
            feedback: feedback
        };
    }
    // Update password strength indicator
    $('[name="password"]').on('input', function() {
        const password = $(this).val();
        const strength = checkPasswordStrength(password);
        const strengthBar = $(this).closest('.form-group').find('.password-strength');
        const feedback = $(this).closest('.form-group').find('.password-feedback');
        strengthBar.removeClass('strength-weak strength-medium strength-strong strength-very-strong');
        if (strength.score <= 2) {
            strengthBar.addClass('strength-weak');
            feedback.text('Weak password. ' + strength.feedback.join('. '));
        } else if (strength.score === 3) {
            strengthBar.addClass('strength-medium');
            feedback.text('Medium strength. ' + strength.feedback.join('. '));
        } else if (strength.score === 4) {
            strengthBar.addClass('strength-strong');
            feedback.text('Strong password. ' + strength.feedback.join('. '));
        } else {
            strengthBar.addClass('strength-very-strong');
            feedback.text('Very strong password!');
        }
    });
    // Show/hide password toggle
    $('.toggle-password').on('click', function() {
        var input = $($(this).attr('toggle'));
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });
    // OTP logic
    var otpCooldown = 60; // seconds
    var otpTimer = null;
    function startOTPTimer() {
        var btn = $('#send-otp-btn');
        btn.prop('disabled', true);
        var remaining = otpCooldown;
        btn.text('Resend OTP (' + remaining + 's)');
        otpTimer = setInterval(function() {
            remaining--;
            btn.text('Resend OTP (' + remaining + 's)');
            if (remaining <= 0) {
                clearInterval(otpTimer);
                btn.prop('disabled', false).text('Send OTP');
            }
        }, 1000);
    }
    $('#send-otp-btn').on('click', function() {
        var btn = $(this);
        if (btn.prop('disabled')) return;
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
        $.ajax({
            url: _base_url_ + 'classes/Master.php?f=send_password_change_otp',
            method: 'POST',
            data: { id: $('[name="id"]').val() },
            dataType: 'json',
            error: function(err) {
                alert_toast('An error occurred while sending OTP', 'error');
                btn.prop('disabled', false).text('Send OTP');
            },
            success: function(resp) {
                if(resp.status === 'success') {
                    alert_toast('OTP sent to your email!', 'success');
                    startOTPTimer();
                } else {
                    alert_toast(resp.msg || 'Failed to send OTP', 'error');
                    btn.prop('disabled', false).text('Send OTP');
                }
            }
        });
    });
    $('#change_password_form').submit(function(e){
        e.preventDefault();
        start_loader();
        if($('.err-msg').length > 0)
            $('.err-msg').remove();
        var newpass = $('#change_password_form [name="password"]').val();
        var confirmpass = $('#change_password_form [name="confirm_password"]').val();
        var otp = $('#change_password_form [name="otp"]').val();
        if(newpass !== confirmpass) {
            var _err_el = $('<div>')
                _err_el.addClass("alert alert-danger err-msg").text('New password fields do not match!')
            $('#change_password_form').prepend(_err_el)
            $('body, html').animate({scrollTop:0},'fast')
            end_loader();
            return false;
        }
        if(!otp) {
            var _err_el = $('<div>')
                _err_el.addClass("alert alert-danger err-msg").text('Please enter the OTP sent to your email!')
            $('#change_password_form').prepend(_err_el)
            $('body, html').animate({scrollTop:0},'fast')
            end_loader();
            return false;
        }
        $.ajax({
            url:_base_url_+"classes/Master.php?f=change_password",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Password successfully changed!",'success');
                    $('#change_password_form input[type="password"]').val('');
                    $('#change_password_form input[name="otp"]').val('');
                    // Show success message with options
                    var successMsg = $('<div class="alert alert-success">')
                        .html('<strong>Success!</strong> Your password has been changed successfully.<br>' +
                              '<small class="text-muted">Redirecting to home page in 3 seconds... <a href="./" class="alert-link">Go now</a> | <a href="./?p=my_account" class="alert-link">Stay in account</a></small>');
                    $('#change_password_form').prepend(successMsg);
                    setTimeout(function(){
                        window.location.href = './';
                    }, 3000);
                }else if(resp.status == 'failed' && !!resp.msg){
                    var _err_el = $('<div>')
                        _err_el.addClass("alert alert-danger err-msg").text(resp.msg)
                    $('#change_password_form').prepend(_err_el)
                    $('body, html').animate({scrollTop:0},'fast')
                    end_loader()
                }else{
                    console.log(resp)
                    alert_toast("an error occured",'error')
                }
                end_loader()
            }
        })
    })
})
</script> 