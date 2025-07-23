<style>
    #uni_modal .modal-content>.modal-footer,#uni_modal .modal-content>.modal-header{
        display:none;
    }
    
    /* Enhanced Modern Registration Modal Design */
    #uni_modal .modal-dialog {
        max-width: 800px;
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
        max-height: 80vh;
        overflow-y: auto;
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
    
    #uni_modal .custom-select {
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #212529;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 100%;
        overflow: visible;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    #uni_modal .custom-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
        color: #212529;
        transform: translateY(-2px);
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
    
    /* Password Strength Indicator */
    .password-strength {
        height: 6px;
        margin-top: 8px;
        border-radius: 3px;
        transition: all 0.3s ease-in-out;
        background: #e1e8ed;
        position: relative;
        overflow: hidden;
    }
    
    .password-strength::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        border-radius: 3px;
        transition: all 0.3s ease;
    }
    
    .strength-weak::before {
        background: linear-gradient(90deg, #ff7675, #fd79a8);
        width: 25%;
    }
    
    .strength-medium::before {
        background: linear-gradient(90deg, #fdcb6e, #e17055);
        width: 50%;
    }
    
    .strength-strong::before {
        background: linear-gradient(90deg, #00b894, #00cec9);
        width: 75%;
    }
    
    .strength-very-strong::before {
        background: linear-gradient(90deg, #00b894, #55a3ff);
        width: 100%;
    }
    
    .password-feedback {
        font-size: 0.85rem;
        margin-top: 8px;
        color: #74b9ff;
        font-weight: 500;
        min-height: 20px;
    }
    
    /* Toggle Password Styling */
    .toggle-password {
        background: #667eea;
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: absolute;
        top: 38px;
        right: 10px;
        cursor: pointer;
    }
    
    .toggle-password:hover {
        background: #5a67d8;
        transform: scale(1.1);
    }
    
    /* Address Section Styling */
    .address-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem 0;
        border: 2px solid #e1e8ed;
        position: relative;
    }
    
    .address-section::before {
        content: '\f3c5';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: -15px;
        left: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .address-section h5 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-left: 2rem;
    }
    
    /* Link Styling */
    #uni_modal a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }
    
    #uni_modal a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #667eea;
        transition: width 0.3s ease;
    }
    
    #uni_modal a:hover::after {
        width: 100%;
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
        
        .address-section {
            padding: 1rem;
        }
        
        #uni_modal .row {
            display: block !important;
        }
        
        #uni_modal .col-lg-5,
        #uni_modal .col-lg-7 {
            width: 100% !important;
            margin-bottom: 1rem;
        }
    }
    
    /* Loading Animation */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    /* Custom Scrollbar */
    #uni_modal .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    
    #uni_modal .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #uni_modal .modal-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }
    
    #uni_modal .modal-body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6a4c93 100%);
    }
</style>
<div class="container-fluid">
    <form id="registration">
        <div class="row">
        
        <h3 class="text-center">
            Create New Account
            <span class="float-right">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </span>
        </h3>
            <hr>
        </div>
        <!-- Personal Information and Login Info Section -->
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="" class="control-label">Firstname</label>
                    <input type="text" class="form-control form-control-sm form" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Lastname</label>
                    <input type="text" class="form-control form-control-sm form" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Contact</label>
                    <input type="text" class="form-control form-control-sm form" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Gender</label>
                    <select name="gender" id="" class="custom-select select" required>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="" class="control-label">Email</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm form" name="email" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary btn-sm" id="send-otp" style="display:none;">Send OTP</button>
                        </div>
                    </div>
                    <small class="text-muted">Please verify your email after registration.</small>
                    <div id="email-status"></div>
                </div>
                <div class="form-group" style="position:relative;">
                    <label for="" class="control-label">Password</label>
                    <input type="password" class="form-control form-control-sm form" name="password" id="reg-password" required>
                    <span id="toggle-password" toggle="#reg-password" class="toggle-password"><i class="fa fa-eye-slash"></i></span>
                    <div class="password-strength"></div>
                    <div class="password-feedback"></div>
                </div>
                <div class="form-group" style="position:relative;">
                    <label for="" class="control-label">Confirm Password</label>
                    <input type="password" class="form-control form-control-sm form" name="confirm_password" id="reg-confirm-password" required>
                    <span id="toggle-confirm-password" toggle="#reg-confirm-password" class="toggle-password"><i class="fa fa-eye-slash"></i></span>
                </div>
            </div>
        </div>
        
        <!-- Address Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="address-section">
                    <h5><i class="fas fa-map-marker-alt mr-2"></i>Default Delivery Address</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="region" class="control-label">Region</label>
                                <select class="custom-select form" name="region" id="region" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province" class="control-label">Province</label>
                                <select class="custom-select form" name="province" id="province" required disabled>
                                    <option value="">Select Province</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="control-label">City/Municipality</label>
                                <select class="custom-select form" name="city" id="city" required disabled>
                                    <option value="">Select City/Municipality</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barangay" class="control-label">Barangay</label>
                                <select class="custom-select form" name="barangay" id="barangay" required disabled>
                                    <option value="">Select Barangay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street_address" class="control-label">Street Address / Building / Unit</label>
                        <textarea class="form-control form" rows='2' name="street_address" id="street_address" placeholder="Enter specific address details (e.g., Block 1 Lot 2, Building Name, Unit Number)"></textarea>
                    </div>
                    <input type="hidden" name="default_delivery_address" id="full_address">
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <a href="javascript:void()" id="login-show">Already have an Account</a>
                    <div>
                        <button class="btn btn-primary btn-flat">Register</button>
                        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<script>
    $(function(){
        let isEmailVerified = false;
        
        $('#login-show').click(function(){
            uni_modal("","/login.php")
        })

        // Form submission handler for the main registration form
        $('#registration').submit(function(e){
            e.preventDefault(); // Prevent default form submission

            // Perform client-side password checks
            const password = $('[name="password"]').val();
            const confirm_password = $('[name="confirm_password"]').val();

            if (password !== confirm_password) {
                alert_toast("Passwords do not match", 'warning');
                return false;
            }

            const strength = checkPasswordStrength(password);
            
            if (strength.score < 3) {
                alert_toast("Password is too weak. Please make it stronger.",'warning')
                return false;
            }

            // Submit registration data
            start_loader();
            $.ajax({
                url: _base_url_+"classes/Master.php?f=register",
                method: "POST",
                data: $('#registration').serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred during registration.", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if(typeof resp == 'object' && resp.status == 'success') {
                        // Show the email verification modal after successful registration
                        alert_toast("Registration successful! Please verify your email.", 'success');
                        setTimeout(function(){
                            if(resp.token){
                                uni_modal("Email Verification", "customer/verify_email.php?token=" + encodeURIComponent(resp.token));
                            } else {
                                uni_modal("Email Verification", "customer/verify_email.php");
                            }
                        }, 1000);
                    } else if(resp.status == 'failed' && !!resp.msg) {
                        alert_toast(resp.msg, 'warning');
                        end_loader();
                    } else {
                        alert_toast("Registration failed. Please try again.", 'error');
                        end_loader();
                    }
                }
            });
        });

        // Handle email verification success (no longer needed here)
        $(document).off('email_verified');

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) {
                strength += 1;
            } else {
                feedback.push("Password should be at least 8 characters long");
            }

            // Contains number
            if (/\d/.test(password)) {
                strength += 1;
            } else {
                feedback.push("Add numbers to make it stronger");
            }

            // Contains lowercase
            if (/[a-z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push("Add lowercase letters");
            }

            // Contains uppercase
            if (/[A-Z]/.test(password)) {
                strength += 1;
            } else {
                feedback.push("Add uppercase letters");
            }

            // Contains special character
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
            // Use closest .form-group to find the strength and feedback divs
            const strengthBar = $(this).closest('.form-group').find('.password-strength');
            const feedback = $(this).closest('.form-group').find('.password-feedback');

            // Remove all strength classes
            strengthBar.removeClass('strength-weak strength-medium strength-strong strength-very-strong');

            // Update strength bar
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

        function updateRegisterButton() {
            if (isEmailVerified) {
                $('#registration button[type="submit"]').prop('disabled', false);
            } else {
                $('#registration button[type="submit"]').prop('disabled', true);
            }
        }

        // Call this after OTP verification and on page load
        updateRegisterButton();

        // After successful OTP verification:
        isEmailVerified = true;
        updateRegisterButton();

        // Simple show/hide password toggle
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

        // Philippines Address System
        function loadRegions() {
            $.ajax({
                url: _base_url_ + 'customer/philippines_address.php?action=regions',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#region').empty().append('<option value="">Select Region</option>');
                    $.each(data, function(index, region) {
                        $('#region').append('<option value="' + region.code + '">' + region.name + '</option>');
                    });
                },
                error: function() {
                    console.log('Error loading regions');
                }
            });
        }

        function loadProvinces(regionCode) {
            if (!regionCode) {
                $('#province').empty().append('<option value="">Select Province</option>').prop('disabled', true);
                $('#city').empty().append('<option value="">Select City/Municipality</option>').prop('disabled', true);
                $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', true);
                return;
            }

            $.ajax({
                url: _base_url_ + 'customer/philippines_address.php?action=provinces&region=' + encodeURIComponent(regionCode),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#province').empty().append('<option value="">Select Province</option>').prop('disabled', false);
                    $.each(data, function(index, province) {
                        $('#province').append('<option value="' + province.name + '">' + province.name + '</option>');
                    });
                    $('#city').empty().append('<option value="">Select City/Municipality</option>').prop('disabled', true);
                    $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', true);
                },
                error: function() {
                    console.log('Error loading provinces');
                }
            });
        }

        function loadCities(regionCode, provinceName) {
            if (!regionCode || !provinceName) {
                $('#city').empty().append('<option value="">Select City/Municipality</option>').prop('disabled', true);
                $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', true);
                return;
            }

            $.ajax({
                url: _base_url_ + 'customer/philippines_address.php?action=cities&region=' + encodeURIComponent(regionCode) + '&province=' + encodeURIComponent(provinceName),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city').empty().append('<option value="">Select City/Municipality</option>').prop('disabled', false);
                    $.each(data, function(index, city) {
                        $('#city').append('<option value="' + city.name + '">' + city.name + '</option>');
                    });
                    $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', true);
                },
                error: function() {
                    console.log('Error loading cities');
                }
            });
        }

        function loadBarangays(regionCode, provinceName, cityName) {
            if (!regionCode || !provinceName || !cityName) {
                $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', true);
                return;
            }

            $.ajax({
                url: _base_url_ + 'customer/philippines_address.php?action=barangays&region=' + encodeURIComponent(regionCode) + '&province=' + encodeURIComponent(provinceName) + '&city=' + encodeURIComponent(cityName),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#barangay').empty().append('<option value="">Select Barangay</option>').prop('disabled', false);
                    $.each(data, function(index, barangay) {
                        $('#barangay').append('<option value="' + barangay.name + '">' + barangay.name + '</option>');
                    });
                },
                error: function() {
                    console.log('Error loading barangays');
                }
            });
        }

        function updateFullAddress() {
            var region = $('#region option:selected').text();
            var province = $('#province').val();
            var city = $('#city').val();
            var barangay = $('#barangay').val();
            var street = $('#street_address').val();

            var fullAddress = '';
            if (street) fullAddress += street + ', ';
            if (barangay) fullAddress += barangay + ', ';
            if (city) fullAddress += city + ', ';
            if (province) fullAddress += province + ', ';
            if (region && region !== 'Select Region') fullAddress += region;

            // Remove trailing comma and space
            fullAddress = fullAddress.replace(/,\s*$/, '');
            
            $('#full_address').val(fullAddress);
        }

        // Address dropdown event handlers
        $('#region').on('change', function() {
            var regionCode = $(this).val();
            loadProvinces(regionCode);
            updateFullAddress();
        });

        $('#province').on('change', function() {
            var regionCode = $('#region').val();
            var provinceName = $(this).val();
            loadCities(regionCode, provinceName);
            updateFullAddress();
        });

        $('#city').on('change', function() {
            var regionCode = $('#region').val();
            var provinceName = $('#province').val();
            var cityName = $(this).val();
            loadBarangays(regionCode, provinceName, cityName);
            updateFullAddress();
        });

        $('#barangay, #street_address').on('change keyup', function() {
            updateFullAddress();
        });

        // Load regions on page load
        loadRegions();
    })
</script>
