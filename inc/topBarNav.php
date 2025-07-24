<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #0d6efd; z-index: 1030; padding-bottom: 15px;">
            <div class="container px-4 px-lg-5 " style="padding-top: 15px;">
                <button class="navbar-toggler btn btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="navbar-brand" href="./" style="color: #fff;">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                <?php echo $_settings->info('short_name') ?>
                </a>

                <form class="form-inline" id="search-form">
                  <div class="input-group">
                    <input class="form-control form-control-sm form " type="search" placeholder="Search" aria-label="Search" name="search"  value="<?php echo isset($_GET['search']) ? $_GET['search'] : "" ?>"  aria-describedby="button-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-light btn-sm m-0" type="submit" id="button-addon2" style="background-color: #ffda47;"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" aria-current="page" href="./">Home</a></li>
                        <?php 
                        $cat_qry = $conn->query("SELECT * FROM categories where status = 1 ");
                        while($crow = $cat_qry->fetch_assoc()):
                          $sub_qry = $conn->query("SELECT * FROM sub_categories where status = 1 and parent_id = '{$crow['id']}' ");
                          if($sub_qry->num_rows <= 0):
                        ?>
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" aria-current="page" href="./?p=products&c=<?php echo md5($crow['id']) ?>"><?php echo $crow['category'] ?></a></li>
                        
                        <?php else: ?>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" style="color: #fff;" id="navbarDropdown<?php echo $crow['id'] ?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><?php echo $crow['category'] ?></a></a>
                            <ul class="dropdown-menu  p-0" aria-labelledby="navbarDropdown<?php echo $crow['id'] ?>">
                              <?php while($srow = $sub_qry->fetch_assoc()): ?>
                                <li><a class="dropdown-item border-bottom" href="./?p=products&c=<?php echo md5($crow['id']) ?>&s=<?php echo md5($srow['id']) ?>"><?php echo $srow['sub_category'] ?></a></li>
                            <?php endwhile; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                    <div class="d-flex align-items-center">
                      <?php if(!isset($_SESSION['userdata']['id'])): ?>
                        <button class="btn btn-light ml-2" id="login-btn" type="button">Login</button>
                        <?php else: ?>
                        <a class="text-light mr-2 nav-link" href="./?p=cart">
                            <i class="fa fa-shopping-cart"></i>
                            Cart
                            <span class="badge text-primary ms-1 rounded-pill" id="cart-count" style="background-color: <?php 
                              if(isset($_SESSION['userdata']['id'])): 
                                $count = $conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$_settings->userdata('id'))->fetch_assoc()['items'];
                                echo ($count > 0 ? '#ffda47' : '#fff');
                              else:
                                echo '#fff';
                              endif;
                            ?> !important;">
                              <?php 
                              if(isset($_SESSION['userdata']['id'])):
                                $count = $conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$_settings->userdata('id'))->fetch_assoc()['items'];
                                echo ($count > 0 ? $count : 0);
                              else:
                                echo "0";
                              endif;
                              ?>
                            </span>
                        </a>
                            <div class="dropdown">
                                <a href="#" class="text-light nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user"></i> <b>Hi, <?php echo $_settings->userdata('firstname')?>!</b>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="./?p=my_account">
                                        <i class="fa fa-shopping-bag mr-2"></i> My Orders
                                    </a>
                                    <a class="dropdown-item" href="./?p=edit_account">
                                        <i class="fa fa-user-cog mr-2"></i> Edit Account
                                    </a>
                                    <a class="dropdown-item" href="./?p=change_password">
                                        <i class="fa fa-key mr-2"></i> Change Password
                                    </a>
                                </div>
                            </div>
                            <a href="javascript:void(0)" onclick="confirmLogout()" class="text-light nav-link"><i class="fa fa-sign-out-alt"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border: 2px solid #0d6efd; border-top-left-radius: 0; border-top-right-radius: 0; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <div class="modal-header" style="background-color: #0d6efd; color: #fff; border-top-left-radius: 0; border-top-right-radius: 0;">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color: #fffae6; color: #333;">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer" style="background-color: #e3f0ff; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #0d6efd; color: #fff; border-radius: 20px;">Cancel</button>
        <a href="customer/logout.php" class="btn btn-primary" style="background-color: #ffda47; color: #0d6efd; border-radius: 20px; border: none;">Logout</a>
      </div>
    </div>
  </div>
</div>
<script>
  $(function(){
    $('#login-btn').click(function(){
      uni_modal("","customer/login.php")
    })
    $('#navbarResponsive').on('show.bs.collapse', function () {
        $('#mainNav').addClass('navbar-shrink')
    })
    $('#navbarResponsive').on('hidden.bs.collapse', function () {
        if($('body').offset.top == 0)
          $('#mainNav').removeClass('navbar-shrink')
    })
  })

  function confirmLogout() {
    $('#logoutModal').modal('show');
  }

  $('#search-form').submit(function(e){
    e.preventDefault()
     var sTxt = $('[name="search"]').val()
     if(sTxt != '')
      location.href = './?p=products&search='+sTxt;
  })
</script>
<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
  .nav-link {
    transition: all 0.2s ease;
  }
  .nav-link:hover {
    opacity: 0.8;
  }
  .btn-light {
    transition: all 0.2s ease;
  }
  .btn-light:hover {
    opacity: 0.9;
  }
  .dropdown-item {
    transition: all 0.2s ease;
  }
  .dropdown-item:hover {
    background-color: #0d6efd;
    color: #fff;
  }
</style>