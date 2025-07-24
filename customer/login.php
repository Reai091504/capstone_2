<style>
    #uni_modal .modal-content>.modal-footer,#uni_modal .modal-content>.modal-header{
        display:none;
    }
    
    /* Enhanced Modern Login Modal Design */
    #uni_modal .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
    
    #uni_modal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: visible;
        background: #ffffff;
        position: relative;
    }
    
    #uni_modal .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
        background-size: 300% 300%;
        animation: gradientShift 3s ease infinite;
        border-radius: 20px 20px 0 0;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    #uni_modal .modal-body {
        background: #ffffff;
        padding: 3rem 2.5rem 2.5rem 2.5rem;
        margin: 0;
        position: relative;
        border-radius: 20px;
    }
    
    #uni_modal .modal-body::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        z-index: 1;
    }
    
    #uni_modal .modal-body::after {
        content: '\f007';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 2rem;
        z-index: 2;
    }
    
    /* Header Styling */
    #uni_modal h3 {
        text-align: center;
        margin-top: 2rem;
        margin-bottom: 2rem;
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.8rem;
        position: relative;
    }
    
    #uni_modal .close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 18px;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    #uni_modal .close:hover {
        background: #ff4757;
        color: white;
        transform: rotate(90deg);
    }
    
    /* Form Styling */
    #uni_modal .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    #uni_modal .form-control {
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    #uni_modal .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
        transform: translateY(-2px);
    }
    
    #uni_modal .control-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    
    /* Input Group Styling */
    #uni_modal .input-group-text {
        background: #667eea;
        border: 2px solid #667eea;
        color: white;
        border-radius: 0 12px 12px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    #uni_modal .input-group-text:hover {
        background: #5a67d8;
        transform: scale(1.05);
    }
    
    #uni_modal .input-group .form-control {
        border-right: none;
        border-radius: 12px 0 0 12px;
    }
    
    /* Button Styling */
    #uni_modal .btn {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    #uni_modal .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    #uni_modal .btn:hover::before {
        left: 100%;
    }
    
    #uni_modal .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    #uni_modal .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }
    
    #uni_modal .btn-secondary {
        background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
        color: #2c3e50;
        box-shadow: 0 8px 25px rgba(255, 234, 167, 0.3);
    }
    
    #uni_modal .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(255, 234, 167, 0.4);
        color: #2c3e50;
    }
    
    #uni_modal .btn-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }
    
    #uni_modal .btn-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #667eea;
        transition: width 0.3s ease;
    }
    
    #uni_modal .btn-link:hover::after {
        width: 100%;
    }
    
    #uni_modal .btn-link:hover {
        color: #5a67d8;
        text-decoration: none;
    }
    
    /* Alert Styling */
    #uni_modal .alert {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
        margin-top: 1rem;
        position: relative;
        overflow: hidden;
    }
    
    #uni_modal .alert-warning {
        background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
        color: #2c3e50;
        box-shadow: 0 5px 15px rgba(255, 234, 167, 0.3);
    }
    
    #uni_modal .alert-danger {
        background: linear-gradient(135deg, #ff7675 0%, #fd79a8 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(255, 118, 117, 0.3);
    }
    
    /* Link Styling */
    #uni_modal a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    #uni_modal a:hover {
        color: #5a67d8;
        text-decoration: none;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        #uni_modal .modal-body {
            padding: 2rem 1.5rem;
        }
        
        #uni_modal .modal-body::before {
            width: 80px;
            height: 80px;
            top: -40px;
        }
        
        #uni_modal .modal-body::after {
            top: -15px;
            font-size: 1.5rem;
        }
        
        #uni_modal h3 {
            font-size: 1.5rem;
            margin-top: 1.5rem;
        }
        
        #uni_modal .btn {
            padding: 10px 25px;
            font-size: 0.95rem;
        }
    }
    
    /* Loading Animation */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    /* OTP Section Styling */
    #uni_modal #login-otp-section {
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Forgot Password Link */
    #uni_modal #forgot_password {
        color: #95a5a6;
        cursor: default;
        font-size: 0.9rem;
    }
</style>
<div class="container-fluid">
    
    <div class="row">
    <h3 class="float-right">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </h3>
        <div class="col-lg-12">
            <h3 class="text-center">Login</h3>
            <hr>
            <!-- Login with Password (default) -->
            <div id="login-password-section">
                <form action="" id="login-form">
                    <div class="form-group">
                        <label for="" class="control-label">Email</label>
                        <input type="email" class="form-control form" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control form" name="password" id="login-password" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggle-password" style="cursor:pointer;">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                        <span id="forgot_password" style="color: #6c757d; cursor: default; text-decoration: none;">Forgot Password?</span>
                        <button type="button" id="login_with_otp" class="btn btn-link p-0 m-0 align-baseline" style="font-size: 0.95em;">Log in with OTP instead</button>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <a href="javascript:void()" id="create_account">Create Account</a>
                        <div>
                            <button class="btn btn-primary btn-flat">Login</button>
                            <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
                <div class="alert alert-warning mt-3 text-center" style="background-color: #ffda47; color: #444; border: 1px solid #0d6efd; border-radius: 8px; font-weight: 500;">
                  <i class="fa fa-exclamation-triangle mr-2"></i>
                  For the best experience using oyeepetshop, we recommend using Google Chrome on a desktop.
                </div>
            </div>

            <!-- Login with OTP (hidden by default) -->
            <div id="login-otp-section" style="display:none;">
                <form id="otp-login-form">
                    <div class="form-group">
                        <label for="otp-email" class="control-label">Email</label>
                        <input type="email" class="form-control form" id="otp-email" name="email" required>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary btn-flat" id="send-otp-login">Send OTP</button>
                        <button type="button" class="btn btn-secondary btn-flat ml-2" id="resend-otp-login" style="display:none;" disabled>Resend OTP (60s)</button>
                    </div>
                    <div class="form-group" id="otp-input-group" style="display:none;">
                        <label for="otp-code" class="control-label">Enter OTP</label>
                        <input type="text" class="form-control form" id="otp-code" name="otp" required>
                        <button type="submit" class="btn btn-success btn-flat mt-2">Log in with OTP</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-link p-0 m-0 align-baseline" id="back-to-password-login" style="font-size: 0.95em;">Back to password login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#create_account').click(function(){
            uni_modal("","customer/registration.php","mid-large")
        })
        $('#login-form').submit(function(e){
            e.preventDefault();
            start_loader()
            if($('.err-msg').length > 0)
                $('.err-msg').remove();
            $.ajax({
                url:_base_url_+"classes/Login.php?f=login_user",
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                error:err=>{
                    console.log(err)
                    var _err_el = $('<div>')
                        _err_el.addClass("alert alert-danger err-msg").text("An error occurred: " + err.responseText)
                    $('#login-form').prepend(_err_el)
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        alert_toast("Login Successfully",'success')
                        setTimeout(function(){
                            location.href = "/"
                        },2000)
                    }else if(resp.status == 'incorrect'){
                        var _err_el = $('<div>')
                            _err_el.addClass("alert alert-danger err-msg").text("Incorrect Credentials.")
                        $('#login-form').prepend(_err_el)
                        end_loader()
                    }else if(resp.status == 'failed'){
                        var _err_el = $('<div>')
                            _err_el.addClass("alert alert-danger err-msg").text("Database Error: " + resp._error)
                        $('#login-form').prepend(_err_el)
                        end_loader()
                    }else{
                        var _err_el = $('<div>')
                            _err_el.addClass("alert alert-danger err-msg").text("An error occurred: " + JSON.stringify(resp))
                        $('#login-form').prepend(_err_el)
                        end_loader()
                    }
                }
            })
        })
        // Show/Hide password toggle
        $('#toggle-password').on('click', function() {
            var passwordInput = $('#login-password');
            var icon = $(this).find('i');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        // Show OTP login section
        $('#login_with_otp').on('click', function() {
            $('#login-password-section').hide();
            $('#login-otp-section').show();
        });

        // Back to password login
        $('#back-to-password-login').on('click', function() {
            $('#login-otp-section').hide();
            $('#login-password-section').show();
        });

        var otpResendTimer = null;
        var otpResendTimeLeft = 60;
        function startOtpResendTimer() {
            otpResendTimeLeft = 60;
            $('#resend-otp-login').prop('disabled', true).show().text('Resend OTP (' + otpResendTimeLeft + 's)');
            $('#send-otp-login').prop('disabled', true);
            otpResendTimer = setInterval(function() {
                otpResendTimeLeft--;
                if (otpResendTimeLeft <= 0) {
                    clearInterval(otpResendTimer);
                    $('#resend-otp-login').prop('disabled', false).text('Resend OTP');
                    $('#send-otp-login').prop('disabled', false);
                } else {
                    $('#resend-otp-login').text('Resend OTP (' + otpResendTimeLeft + 's)');
                }
            }, 1000);
        }
        $('#send-otp-login').on('click', function() {
            var email = $('#otp-email').val();
            if (!email) {
                alert_toast('Please enter your email.', 'warning');
                return;
            }
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Sending...');
            $.ajax({
                url: _base_url_ + "classes/Users.php?f=send_login_otp",
                method: "POST",
                data: { email: email },
                dataType: "json",
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                },
                success: function(resp) {
                    if (resp.status === 'success') {
                        $('#otp-input-group').show();
                        alert_toast('OTP sent to your email.', 'success');
                        startOtpResendTimer();
                    } else {
                        alert_toast(resp.msg, 'error');
                    }
                }
            });
        });
        $('#resend-otp-login').on('click', function() {
            var email = $('#otp-email').val();
            if (!email) {
                alert_toast('Please enter your email.', 'warning');
                return;
            }
            var btn = $(this);
            btn.prop('disabled', true).text('Sending...');
            $.ajax({
                url: _base_url_ + "classes/Users.php?f=send_login_otp",
                method: "POST",
                data: { email: email },
                dataType: "json",
                complete: function() {
                    // handled by timer
                },
                success: function(resp) {
                    if (resp.status === 'success') {
                        alert_toast('OTP resent to your email.', 'success');
                        startOtpResendTimer();
                    } else {
                        alert_toast(resp.msg, 'error');
                        $('#resend-otp-login').prop('disabled', false).text('Resend OTP');
                    }
                }
            });
        });

        $('#otp-login-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: _base_url_ + "classes/Users.php?f=verify_login_otp",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(resp) {
                    if (resp.status === 'success') {
                        alert_toast('Login successful!', 'success');
                        setTimeout(function() {
                            location.href = _base_url_;
                        }, 1500);
                    } else {
                        alert_toast(resp.msg, 'error');
                    }
                }
            });
        });
    })
</script>