<section class="py-5">
    <div class="container" style="margin-top: 100px; max-width: 1000px;">
        <div class="card rounded-4 shadow-lg border-0" style="background: rgba(255,255,255,0.97); box-shadow: 0 8px 32px rgba(60,141,188,0.12);">
            <div class="card-body p-5">
                <div class="w-100 justify-content-between d-flex align-items-center mb-3">
                    <h4 class="mb-0" style="color: #0d6efd;"><b>Update Account Details</b></h4>
                    <a href="./?p=my_account" class="btn btn btn-dark btn-flat"><div class="fa fa-angle-left"></div> Back to Order List</a>
                </div>
                <hr class="border-primary mb-4">
                <div class="d-flex justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="" id="update_account">
                            <input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
                            <div class="form-group">
                                <label for="firstname" class="control-label">Firstname</label>
                                <input type="text" name="firstname" class="form-control form" value="<?php echo $_settings->userdata('firstname') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="control-label">Lastname</label>
                                <input type="text" name="lastname" class="form-control form" value="<?php echo $_settings->userdata('lastname') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Contact</label>
                                <input type="text" class="form-control form-control-sm form" name="contact" value="<?php echo $_settings->userdata('contact') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Gender</label>
                                <select name="gender" id="" class="custom-select select" required>
                                    <option <?php echo $_settings->userdata('gender') == "Male" ? "selected" : '' ?>>Male</option>
                                    <option <?php echo $_settings->userdata('gender') == "Female" ? "selected" : '' ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Default Delivery Address</label>
                                <textarea class="form-control form" rows='3' name="default_delivery_address"><?php echo $_settings->userdata('default_delivery_address') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control form" value="<?php echo $_settings->userdata('email') ?>" required>
                                <button type="button" id="send_otp_btn" class="btn btn-sm btn-primary mt-2">Send OTP</button>
                                <div id="otp_section" style="display:none; margin-top:10px;">
                                    <label for="email_otp" class="control-label">Enter OTP</label>
                                    <input type="text" name="email_otp" id="email_otp" class="form-control form" maxlength="6" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <button class="btn btn-dark btn-flat">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(function(){
    var originalEmail = $('input[name="email"]').val();
    var otpSent = false;

    function toggleOtpFields() {
        var newEmail = $('#email').val();
        if(newEmail !== originalEmail) {
            $('#send_otp_btn').show();
            if(otpSent) {
                $('#otp_section').show();
            }
        } else {
            $('#send_otp_btn').hide();
            $('#otp_section').hide();
            otpSent = false;
            $('#email_otp').val('');
        }
    }

    // Initial state
    $('#send_otp_btn').hide();
    $('#otp_section').hide();

    $('#email').on('input', function(){
        otpSent = false;
        $('#otp_section').hide();
        $('#email_otp').val('');
        toggleOtpFields();
    });

    $('#send_otp_btn').click(function(){
        var newEmail = $('#email').val();
        if(!newEmail || newEmail === originalEmail) {
            alert_toast('Please enter a new email address to send OTP.','warning');
            return;
        }
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=send_email_change_otp",
            method: "POST",
            data: { email: newEmail },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred while sending OTP.", 'error');
                end_loader();
            },
            success: function(resp) {
                if(resp.status === 'success') {
                    $('#otp_section').show();
                    otpSent = true;
                    alert_toast('OTP sent to your new email.','success');
                } else {
                    alert_toast(resp.msg || 'Failed to send OTP.','error');
                }
                end_loader();
            }
        });
    });

    $('#update_account').submit(function(e){
        e.preventDefault();
        start_loader();
        if($('.err-msg').length > 0)
            $('.err-msg').remove();
        var newEmail = $('#email').val();
        var formData = $(this).serializeArray();
        if(newEmail !== originalEmail) {
            if(!otpSent) {
                alert_toast('Please send and enter the OTP for your new email.','warning');
                end_loader();
                return;
            }
            var otp = $('#email_otp').val();
            if(!otp || otp.length !== 6) {
                alert_toast('Please enter the 6-digit OTP sent to your new email.','warning');
                end_loader();
                return;
            }
            formData.push({name: 'email_otp', value: otp});
        }
        $.ajax({
            url:_base_url_+"classes/Master.php?f=update_account",
            method:"POST",
            data:formData,
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Account succesfully updated",'success');
                    // Hide OTP and Send OTP after successful update
                    originalEmail = $('#email').val();
                    otpSent = false;
                    $('#otp_section').hide();
                    $('#send_otp_btn').hide();
                    $('#email_otp').val('');
                }else if(resp.status == 'verify_email'){
                    var _info_el = $('<div>')
                        _info_el.addClass("alert alert-info err-msg").text(resp.msg)
                    $('#update_account').prepend(_info_el)
                    $('body, html').animate({scrollTop:0},'fast')
                    end_loader()
                }else if(resp.status == 'failed' && !!resp.msg){
                    var _err_el = $('<div>')
                        _err_el.addClass("alert alert-danger err-msg").text(resp.msg)
                    $('#update_account').prepend(_err_el)
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
    // Initial toggle
    toggleOtpFields();
})
</script>
<style>
.card.rounded-4 {
    border-radius: 1.5rem !important;
}
#update_account .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
}
#update_account label {
    color: #3c8dbc;
    font-weight: 500;
}
#update_account button.btn-dark {
    background: #0d6efd;
    border: none;
    border-radius: 20px;
    color: #fff;
    transition: background 0.2s;
}
#update_account button.btn-dark:hover {
    background: #084298;
    color: #fff;
}
</style>