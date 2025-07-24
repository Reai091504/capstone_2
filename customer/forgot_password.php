<style>
    #uni_modal .modal-content>.modal-footer,#uni_modal .modal-content>.modal-header{
        display:none;
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
            <h3 class="text-center">Forgot Password</h3>
            <hr>
            <form action="" id="forgot-password-form">
                <div class="form-group">
                    <label for="" class="control-label">Email</label>
                    <input type="email" class="form-control form" name="email" required>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary btn-flat">Recover Password</button>
                </div>
                <div class="form-group text-center">
                    <a href="javascript:void(0)" id="back-to-login">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#forgot-password-form').submit(function(e){
            e.preventDefault();
            start_loader();
            if($('.err-msg').length > 0)
                $('.err-msg').remove();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=recover",
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                error:err=>{
                    console.log(err)
                    var _err_el = $('<div>')
                        _err_el.addClass("alert alert-danger err-msg").text("An error occurred: " + err.responseText)
                    $('#forgot-password-form').prepend(_err_el)
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        alert_toast("If this email is registered, you will receive password recovery instructions.",'success')
                        setTimeout(function(){
                            $('#back-to-login').click();
                        },2000)
                    }else{
                        // Always show the same message for security
                        alert_toast("If this email is registered, you will receive password recovery instructions.",'info')
                        end_loader()
                    }
                }
            })
        });

        $('#back-to-login').click(function(){
            uni_modal('Login','login.php');
        });
    })
</script> 