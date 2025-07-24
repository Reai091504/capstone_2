<script>
  $(document).ready(function(){
    $('#p_use').click(function(){
      uni_modal("Privacy Policy","policy.php","mid-large")
    })
     window.viewer_modal = function($src = ''){
      start_loader()
      var t = $src.split('.')
      t = t[1]
      if(t =='mp4'){
        var view = $("<video src='"+$src+"' controls autoplay></video>")
      }else{
        var view = $("<img src='"+$src+"' />")
      }
      $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
      $('#viewer_modal .modal-content').append(view)
      $('#viewer_modal').modal({
              show:true,
              backdrop:'static',
              keyboard:false,
              focus:true
            })
            end_loader()  

  }
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_loader()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size+'  modal-dialog-centered')
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered")
                    }
                    $('#uni_modal').modal({
                      show:true,
                      backdrop:'static',
                      keyboard:false,
                      focus:true
                    })
                    end_loader()
                }
            }
        })
    }
    window._conf = function($msg='',$func='',$params = []){
       $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
       $('#confirm_modal .modal-body').html($msg)
       $('#confirm_modal').modal('show')
    }
  })
</script>

<!-- Footer-->
<footer class="py-2" style="background-color: #ffda47;">
    <div style="background-color: #0b5ed7; color: #fff; padding: 20px 0; margin-top: -8px;">
        <div class="container" style="display: flex; justify-content: space-around; align-items: center;">
            <div><i class="fas fa-paw"></i> Healthy & Safe Products</div>
            <div><i class="fas fa-fish"></i> Wide Fish Selection</div>
            <div><i class="fas fa-shopping-bag"></i> Pickup or Delivery</div>
        </div>
    </div>
    <div class="container">
        <!-- Logo, Nav Links, Social Icons will go here -->
        <div style="text-align: center; margin-bottom: 3px;">
            <!-- Logo -->
            <a href="index.php" style="display: inline-block;">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Pet Express Logo" style="max-width: 120px; width: 100%; height: auto;">
            </a>
        </div>
        <div style="text-align: center; margin: 5px 0;">
            <!-- Navigation Links -->
            <a href=".?p=about_us" style="color: #000; margin: 0 5px; text-decoration: none;">ABOUT US</a>
            <a href="locations.php" style="color: #000; margin: 0 5px; text-decoration: none;">LOCATIONS</a>
            <a href="customer/Privacy Polic1.pdf" target="_blank" style="color: #000; margin: 0 5px; text-decoration: none;">PRIVACY POLICY</a>
            <a href="customer/Terms and Condition1.pdf" target="_blank" style="color: #000; margin: 0 5px; text-decoration: none;">TERMS & CONDITIONS</a>
        </div>
        <div style="text-align: center; margin-top: 5px;">
            <!-- Social Icons and Partner Logos -->
            <a href="https://www.facebook.com/davaooyeee" target="_blank" style="color: inherit; text-decoration: none;">
                <i class="fab fa-facebook" style="font-size: 1.5rem; margin: 0 10px;"></i>
            </a>
            <a href="https://www.instagram.com/oyeeepetshop" target="_blank" style="color: inherit; text-decoration: none;">
                <i class="fab fa-instagram" style="font-size: 1.5rem; margin: 0 10px;"></i>
            </a>
            <a href="https://www.tiktok.com/@oyeeepetshop?_t=8qWt801l9S4&_r=1" target="_blank" style="color: inherit; text-decoration: none;">
                <i class="fab fa-tiktok" style="font-size: 1.5rem; margin: 0 10px;"></i>
            </a>
            <a href="https://www.youtube.com/@oyeeetv4732" target="_blank" style="color: inherit; text-decoration: none;">
                <i class="fab fa-youtube" style="font-size: 1.5rem; margin: 0 10px;"></i>
            </a>
            <!-- Need to add images for other logos -->
        </div>
    </div>
</footer>

<div style="background-color: #f0c235; color: #000; padding: 10px 0;">
    <div class="container">
        <p class="m-0 text-center text-dark">Copyright &copy; <?php echo $_settings->info('short_name') ?> 2025</p>
    </div>
</div>

   
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="<?php echo base_url ?>plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url ?>plugins/sparklines/sparkline.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
    <!-- JQVMap -->
    <script src="<?php echo base_url ?>plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url ?>plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url ?>plugins/moment/moment.min.js"></script>
    <script src="<?php echo base_url ?>plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo base_url ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="<?php echo base_url ?>plugins/summernote/summernote-bs4.min.js"></script>
    <script src="<?php echo base_url ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- overlayScrollbars -->
    <!-- <script src="<?php echo base_url ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="<?php echo base_url ?>dist/js/adminlte.js"></script>
    
</footer>

<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-wrapper {
    flex: 1 0 auto;
    padding-bottom: 2rem;
}

footer {
    flex-shrink: 0;
    width: 100%;
    background-color: #343a40;
    margin-top: auto;
}
</style>