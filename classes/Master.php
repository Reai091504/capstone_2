<?php
require_once(__DIR__ . '/../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `categories` where `category` = '{$category}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `categories` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Category successfully saved.");
			else
				$this->settings->set_flashdata('success',"Category successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `categories` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_sub_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `sub_categories` where `sub_category` = '{$sub_category}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Sub Category already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `sub_categories` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `sub_categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Sub Category successfully saved.");
			else
				$this->settings->set_flashdata('success',"Sub Category successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_sub_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `sub_categories` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Sub Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_product(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `products` where `product_name` = '{$product_name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Product already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `products` set {$data} ";
			$save = $this->conn->query($sql);
			$id= $this->conn->insert_id;
		}else{
			$sql = "UPDATE `products` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			if(isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0 && !empty($_FILES['img']['tmp_name'][0])){
				$upload_path = "uploads/product_".$id;
				if(!is_dir(base_app.$upload_path))
					mkdir(base_app.$upload_path);
				foreach($_FILES['img']['tmp_name'] as $k => $v){
					if(!empty($_FILES['img']['tmp_name'][$k])){
						move_uploaded_file($_FILES['img']['tmp_name'][$k],base_app.$upload_path.'/'.$_FILES['img']['name'][$k]);
					}
				}
			}
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Product successfully saved.");
			else
				$this->settings->set_flashdata('success',"Product successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `products` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_inventory(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `inventory` where `product_id` = '{$product_id}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Inventory already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `inventory` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `inventory` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Inventory successfully saved.");
			else
				$this->settings->set_flashdata('success',"Inventory successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_inventory(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `inventory` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Invenory successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	public function register(){
		extract($_POST);
		
		// Validate required fields
		$required_fields = [
			'firstname', 'lastname', 'contact', 'gender', 'default_delivery_address', 'password', 'email'
		];
		foreach ($required_fields as $field) {
			if (!isset($$field) || trim($$field) === '') {
				return json_encode(['status' => 'failed', 'msg' => ucfirst(str_replace('_', ' ', $field)) . ' is required.']);
			}
		}
		
		// Check if email already exists
		$check = $this->conn->query("SELECT * FROM `clients` where email = '{$email}'")->num_rows;
		if($check > 0){
			return json_encode(['status' => 'failed', 'msg' => 'Email already exists.']);
		}

	// Store registration data in session for later use
	$_SESSION['pending_registration'] = [
		'firstname' => $firstname,
		'lastname' => $lastname,
		'contact' => $contact,
		'gender' => $gender,
		'default_delivery_address' => $default_delivery_address,
		'password' => password_hash($password, PASSWORD_DEFAULT),
		'email' => $email
	];
	
	// Send OTP via email
	require_once 'EmailService.php';
	$emailService = new EmailService($this->conn);
	
	// Generate and send OTP
	$otp = $emailService->generateOTP();
	
	if($emailService->storeOTP($email, $otp, 'verification') && $emailService->sendOTP($email, $otp, 'verification')){
		return json_encode(['status' => 'success', 'msg' => 'OTP sent successfully. Please verify your email.']);
	} else {
		return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP. Please try again.']);
	}
	}

	public function verify_email(){
		extract($_POST);
		
		try {
			if(!isset($_SESSION['pending_registration'])){
				// Only show this if OTP is not being verified (i.e., not after success)
				return json_encode(['status' => 'failed', 'msg' => '']); // Return empty message
			}
			if(empty($otp)){
				return json_encode(['status' => 'failed', 'msg' => 'OTP is required.']);
			}
			// Get email from session
			$email = $_SESSION['pending_registration']['email'];
			// Verify OTP using EmailService
			require_once 'EmailService.php';
			$emailService = new EmailService($this->conn);
			if(!$emailService->verifyOTP($email, $otp, 'verification')){
				return json_encode(['status' => 'failed', 'msg' => 'Invalid or expired OTP. Please try again.']);
			}
			
			// OTP is valid, proceed with registration
			$registration_data = $_SESSION['pending_registration'];
			
			// Create user account
			$data = " email = '{$email}' ";
			foreach($registration_data as $k => $v){
				if($k != 'email' && !empty($data)) $data .= ", ";
				if($k != 'email') $data .= " `{$k}` = '{$v}' ";
			}
			
			$sql = "INSERT INTO `clients` set {$data}";
			$save = $this->conn->query($sql);
			
			if($save){
				// Clear session data
				unset($_SESSION['pending_registration']);
				
				// Get the newly created user's data
				$user = $this->conn->query("SELECT * FROM `clients` where email = '{$email}'")->fetch_assoc();
				
				// Set user session
				foreach($user as $k => $v){
					if(!in_array($k, array('password'))){
						$this->settings->set_userdata($k, $v);
					}
				}
				$this->settings->set_userdata('login_type', 1);
				
				return json_encode(['status' => 'success', 'msg' => 'Email verified successfully. Account created.']);
			} else {
				error_log("Database error: " . $this->conn->error);
				return json_encode(['status' => 'failed', 'msg' => 'Failed to create account. Please try again.']);
			}
			
		} catch (Exception $e) {
			error_log("Registration error: " . $e->getMessage());
			return json_encode(['status' => 'failed', 'msg' => 'An error occurred: ' . $e->getMessage()]);
		}
	}
	
	function resend_otp(){
		if(!isset($_SESSION['pending_registration'])){
			return json_encode(['status' => 'failed', 'msg' => 'Session expired.']);
		}
		
		$email = $_SESSION['pending_registration']['email'];
		
		// Generate and send new OTP
		require_once 'EmailService.php';
		$emailService = new EmailService($this->conn);
		
		$otp = $emailService->generateOTP();
		
		if($emailService->storeOTP($email, $otp, 'verification') && $emailService->sendOTP($email, $otp, 'verification')){
			return json_encode(['status' => 'success', 'msg' => 'New OTP sent successfully.']);
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP. Please try again.']);
		}
	}

	function add_to_cart(){
		extract($_POST);
		$data = " client_id = '".$this->settings->userdata('id')."' ";
		$_POST['price'] = str_replace(",","",$_POST['price']); 
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `cart` where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id'))->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		
		// Check if there's enough stock
		$stock = $this->conn->query("SELECT quantity FROM `inventory` where id = '{$inventory_id}'")->fetch_assoc()['quantity'];
		if($stock < $quantity){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Not enough stock available';
			return json_encode($resp);
		}

		if($check > 0){
			$sql = "UPDATE `cart` set quantity = quantity + {$quantity} where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id');
		}else{
			$sql = "INSERT INTO `cart` set {$data} ";
		}
		
		$save = $this->conn->query($sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			// Update inventory quantity by subtracting only the added quantity
			$update_stock = $this->conn->query("UPDATE `inventory` set quantity = quantity - {$quantity} where id = '{$inventory_id}'");
			if($this->capture_err())
				return $this->capture_err();
			
			$resp['status'] = 'success';
			$resp['cart_count'] = $this->conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$this->settings->userdata('id'))->fetch_assoc()['items'];
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function update_cart_qty(){
		extract($_POST);
		
		$save = $this->conn->query("UPDATE `cart` set quantity = '{$quantity}' where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
		
	}
	function empty_cart(){
		$delete = $this->conn->query("DELETE FROM `cart` where client_id = ".$this->settings->userdata('id'));
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_cart(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `cart` where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_order(){
		extract($_POST);
		// Restore stock for each product in the order
		$order_items = $this->conn->query("SELECT product_id, quantity FROM `order_list` WHERE order_id = '{$id}'");
		while($item = $order_items->fetch_assoc()){
			$product_id = $item['product_id'];
			$quantity = $item['quantity'];
			// Update inventory quantity by adding back the quantity
			$this->conn->query("UPDATE `inventory` SET quantity = quantity + {$quantity} WHERE product_id = '{$product_id}'");
		}
		$delete = $this->conn->query("DELETE FROM `orders` where id = '{$id}'");
		$delete2 = $this->conn->query("DELETE FROM `order_list` where order_id = '{$id}'");
		$delete3 = $this->conn->query("DELETE FROM `sales` where order_id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Order successfully deleted");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function place_order(){
		extract($_POST);
		$client_id = $this->settings->userdata('id');

		$delivery_fee = isset($delivery_fee) ? floatval($delivery_fee) : 0;
		
		$data = " client_id = '{$client_id}' ";
		$data .= " ,payment_method = '{$payment_method}' ";
		$data .= " ,amount = '{$amount}' ";
		$data .= " ,delivery_fee = '{$delivery_fee}' ";
		$data .= " ,paid = '{$paid}' ";
		$data .= " ,delivery_address = '{$delivery_address}' ";
		$order_sql = "INSERT INTO `orders` set $data";
		$save_order = $this->conn->query($order_sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save_order){
			$order_id = $this->conn->insert_id;
			$data = '';
			$cart = $this->conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid,i.unit,i.id as inventory_id from `cart` c inner join `inventory` i on i.id=c.inventory_id inner join products p on p.id = i.product_id where c.client_id ='{$client_id}' ");
			while($row= $cart->fetch_assoc()):
				if(!empty($data)) $data .= ", ";
				$total = $row['price'] * $row['quantity'];
				$data .= "('{$order_id}','{$row['pid']}','{$row['size']}','{$row['unit']}','{$row['quantity']}','{$row['price']}', $total)";
				
				// Update inventory quantity
				$update_inventory = $this->conn->query("UPDATE `inventory` SET quantity = quantity - {$row['quantity']} WHERE id = '{$row['inventory_id']}'");
				
				if($this->capture_err()){
					return $this->capture_err();
				}
			endwhile;
			
			$list_sql = "INSERT INTO `order_list` (order_id,product_id,size,unit,quantity,price,total) VALUES {$data} ";
			$save_olist = $this->conn->query($list_sql);
			if($this->capture_err())
				return $this->capture_err();
			if($save_olist){
				$empty_cart = $this->conn->query("DELETE FROM `cart` where client_id = '{$client_id}'");
				$data = " order_id = '{$order_id}'";
				$data .= " ,total_amount = '{$amount}'";
				$save_sales = $this->conn->query("INSERT INTO `sales` set $data");
					if($this->capture_err())
						return $this->capture_err();
					$resp['status'] ='success';
			}else{
				$resp['status'] ='failed';
				$resp['err_sql'] =$save_olist;
			}

		}else{
			$resp['status'] ='failed';
			$resp['err_sql'] =$save_order;
		}
		return json_encode($resp);
	}
	function update_order_status(){
		extract($_POST);
		// If status is set to 4 (Cancelled), restore stock
		if($status == 4){
			$order_items = $this->conn->query("SELECT product_id, quantity FROM `order_list` WHERE order_id = '{$id}'");
			while($item = $order_items->fetch_assoc()){
				$product_id = $item['product_id'];
				$quantity = $item['quantity'];
				// Update inventory quantity by adding back the quantity
				$this->conn->query("UPDATE `inventory` SET quantity = quantity + {$quantity} WHERE product_id = '{$product_id}'");
			}
		}
		$save = $this->conn->query("UPDATE `orders` set `status` = '{$status}' where id = '{$id}'");
		if($save){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Order status successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function pay_order(){
		extract($_POST);
		$orderId = null;
		if(isset($id)){
			$orderId = $id;
		} elseif(isset($transaction_id)){
			// Assuming transaction_id corresponds to order_id, adjust if different
			$orderId = $transaction_id;
		}
		if(!$orderId){
			return json_encode(['status' => 'failed', 'msg' => 'Order ID or Transaction ID is required']);
		}
		$update = $this->conn->query("UPDATE `orders` set `paid` = '1' where id = '{$orderId}' ");
		if($update){
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Order payment status successfully updated.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function update_account(){
		extract($_POST);
		$data = "";
		$current_email = $this->settings->userdata('email');
		if(!empty($password)){
			$_POST['password'] = md5($password);
			if(md5($cpassword) != $this->settings->userdata('password')){
				$resp['status'] = 'failed';
				$resp['msg'] = "Current Password is Incorrect";
				return json_encode($resp);
				exit;
			}
		}
		$check = $this->conn->query("SELECT * FROM `clients`  where `email`='{$email}' and `id` != $id ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		// If email is changed, require OTP verification
		if($email !== $current_email){
			if(!isset($_POST['email_otp']) || !$_POST['email_otp']){
				$resp['status'] = 'failed';
				$resp['msg'] = 'OTP is required for email change.';
				return json_encode($resp);
			}
			$otp = $_POST['email_otp'];
			// Check OTP and expiry
			if(!isset($_SESSION['email_change_otp']) || !isset($_SESSION['email_change_otp_expiry']) || !isset($_SESSION['email_change_pending'])){
				$resp['status'] = 'failed';
				$resp['msg'] = 'No OTP session found. Please request a new OTP.';
				return json_encode($resp);
			}
			if($_SESSION['email_change_pending'] !== $email){
				$resp['status'] = 'failed';
				$resp['msg'] = 'OTP does not match the new email.';
				return json_encode($resp);
			}
			if($_SESSION['email_change_otp'] !== $otp){
				$resp['status'] = 'failed';
				$resp['msg'] = 'Invalid OTP.';
				return json_encode($resp);
			}
			if(strtotime($_SESSION['email_change_otp_expiry']) < time()){
				$resp['status'] = 'failed';
				$resp['msg'] = 'OTP has expired. Please request a new one.';
				return json_encode($resp);
			}
			// OTP is valid, clear session
			unset($_SESSION['email_change_otp']);
			unset($_SESSION['email_change_otp_expiry']);
			unset($_SESSION['email_change_pending']);
		}
		foreach($_POST as $k =>$v){
			if($k == 'cpassword' || ($k == 'password' && empty($v)) || $k == 'email_otp')
				continue;
				if(!empty($data)) $data .=",";
					$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("UPDATE `clients` set $data where id = $id ");
		if($save){
			foreach($_POST as $k =>$v){
				if($k != 'cpassword' && $k != 'email_otp')
				$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('id',$id);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	public function send_otp(){
		try {
			extract($_POST);
			
			if(empty($email)){
				return json_encode(['status' => 'failed', 'msg' => 'Email is required.']);
			}
			
			// Email validation
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				return json_encode(['status' => 'failed', 'msg' => 'Please enter a valid email address.']);
			}
			
			// Check if email already exists
			$data = " email = '{$email}' ";
			$chk = $this->conn->query("SELECT * FROM `clients` WHERE {$data}");
			
			if($chk && $chk->num_rows > 0){
				return json_encode(['status' => 'failed', 'msg' => 'Email already exists.']);
			}
			
			// Generate OTP
			$otp = sprintf("%06d", mt_rand(1, 999999));
			$otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
			
			// Store OTP in session
			$_SESSION['otp'] = $otp;
			$_SESSION['otp_expiry'] = $otp_expiry;
			$_SESSION['pending_email'] = $email;
			
			// Send OTP via email
			require_once 'Mailer.php';
			$mailer = new Mailer();
			
			$send_result = $mailer->sendOTP($email, $otp);

			if($send_result === true){
				error_log("OTP sent successfully to: " . $email);
				return json_encode(['status' => 'success', 'msg' => 'OTP sent successfully.']);
			} else {
				error_log("Failed to send OTP to: " . $email . " - Error: " . $send_result);
				return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP. Please try again later. Error: ' . $send_result]);
			}
		} catch (Exception $e) {
			error_log("Error in send_otp: " . $e->getMessage());
			return json_encode(['status' => 'failed', 'msg' => 'An error occurred. Please try again later. Error: ' . $e->getMessage()]);
		}
	}

	public function change_password(){
		extract($_POST);
		if(empty($id) || empty($password) || empty($confirm_password) || empty($otp)){
			return json_encode(['status' => 'failed', 'msg' => 'All fields are required.']);
		}
		if($password !== $confirm_password){
			return json_encode(['status' => 'failed', 'msg' => 'New password and confirm password do not match.']);
		}
		if(strlen($password) < 8){
			return json_encode(['status' => 'failed', 'msg' => 'Password must be at least 8 characters long.']);
		}
		if(!is_numeric($id)){
			return json_encode(['status' => 'failed', 'msg' => 'Invalid user ID.']);
		}
		// Get user email
		$stmt = $this->conn->prepare("SELECT * FROM `clients` WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows == 0){
			$stmt->close();
			return json_encode(['status' => 'failed', 'msg' => 'User not found.']);
		}
		$user_data = $result->fetch_assoc();
		$stmt->close();
		$email = $user_data['email'];
		// Verify OTP
		require_once 'EmailService.php';
		$emailService = new EmailService($this->conn);
		if(!$emailService->verifyOTP($email, $otp, 'password_change')){
			return json_encode(['status' => 'failed', 'msg' => 'Invalid or expired OTP. Please try again.']);
		}
		if(password_verify($password, $user_data['password'])){
			return json_encode(['status' => 'failed', 'msg' => 'New password must be different from current password.']);
		}
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$update_stmt = $this->conn->prepare("UPDATE `clients` SET password = ? WHERE id = ?");
		$update_stmt->bind_param("si", $hashed_password, $id);
		$update_result = $update_stmt->execute();
		$update_stmt->close();
		if($update_result){
			return json_encode(['status' => 'success', 'msg' => 'Password successfully changed.']);
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'Failed to update password. Please try again.']);
		}
	}

	public function send_password_change_otp(){
		extract($_POST);
		if(empty($id)){
			return json_encode(['status' => 'failed', 'msg' => 'User ID is required.']);
		}
		// Get user email
		$stmt = $this->conn->prepare("SELECT email FROM `clients` WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows == 0){
			$stmt->close();
			return json_encode(['status' => 'failed', 'msg' => 'User not found.']);
		}
		$user = $result->fetch_assoc();
		$stmt->close();
		$email = $user['email'];
		require_once 'EmailService.php';
		$emailService = new EmailService($this->conn);
		$otp = $emailService->generateOTP();
		if($emailService->storeOTP($email, $otp, 'password_change') && $emailService->sendOTP($email, $otp, 'password_change')){
			return json_encode(['status' => 'success', 'msg' => 'OTP sent to your email.']);
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP. Please try again.']);
		}
	}

	public function send_email_change_otp() {
		extract($_POST);
		if(empty($email)) {
			return json_encode(['status' => 'failed', 'msg' => 'Email is required.']);
		}
		// Check if email is already taken
		$check = $this->conn->query("SELECT * FROM `clients` WHERE `email`='{$email}'")->num_rows;
		if($check > 0) {
			return json_encode(['status' => 'failed', 'msg' => 'Email already taken.']);
		}
		// Generate OTP
		$otp = sprintf("%06d", mt_rand(1, 999999));
		$otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
		// Store OTP in session (or optionally in DB)
		$_SESSION['email_change_otp'] = $otp;
		$_SESSION['email_change_otp_expiry'] = $otp_expiry;
		$_SESSION['email_change_pending'] = $email;
		// Send OTP
		require_once 'EmailService.php';
		$emailService = new EmailService($this->conn);
		$sent = $emailService->sendOTP($email, $otp, 'verification');
		if($sent) {
			return json_encode(['status' => 'success', 'msg' => 'OTP sent to your new email.']);
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP. Please try again.']);
		}
	}

public function update_paymongo_link() {
    // Input validation
    if(empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
        return json_encode(['success' => false, 'error' => 'Invalid amount']);
    }

    $amount = floatval($_POST['amount']);
    $amountCentavos = intval($amount * 100);

    // Prepare request
    $requestData = [
        'data' => [
            'attributes' => [
                'line_items' => [[
                    'amount' => $amountCentavos,
                    'currency' => 'PHP',
                    'name' => 'Petshop Order',
                    'quantity' => 1
                ]],
'payment_method_types' => ['card', 'gcash', 'paymaya'],
                'success_url' => $this->get_domain() . '/payment_success.php?transaction_id=',
                'cancel_url' => $this->get_domain() . '/payment_failed.php?transaction_id=',
                'billing' => [
                    'name' => $this->get_customer_name(),
                    'email' => $this->settings->userdata('email'),
                    'phone' => $this->settings->userdata('contact')
                ]
            ]
        ]
    ];

    // Make API call
    $response = $this->make_paymongo_request($requestData);
    
    // Handle response
    if(isset($response['data']['attributes']['checkout_url'])) {
        return json_encode([
            'success' => true,
            'checkout_url' => $response['data']['attributes']['checkout_url']
        ]);
    }

    // Error handling
    $error = $this->extract_error_message($response);
    return json_encode(['success' => false, 'error' => $error]);
}

// Helper methods
private function get_domain() {
    $protocol = isset($_SERVER['HTTPS']) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'];
}

private function get_customer_name() {
    return $this->settings->userdata('firstname') . ' ' . $this->settings->userdata('lastname');
}

private function make_paymongo_request($data) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic c2tfdGVzdF81THIxSkNmeVVaY2FyUDNWMkNmY2NBQmk6",
            "Content-Type: application/json"
        ]
    ]);
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}

private function extract_error_message($response) {
    if (isset($response['errors'][0]['detail'])) {
        return $response['errors'][0]['detail'];
    }
    return 'Unknown error (but checkout_url might actually exist)';
}

	public function update_paymaya_link() {
		extract($_POST);

		if(!isset($amount) || !is_numeric($amount)) {
			return json_encode(['success' => false, 'error' => 'Invalid amount']);
		}

		$amountCents = intval($amount * 100); // Convert to cents

		// Get customer info
		$customerName = $this->settings->userdata('firstname') . ' ' . $this->settings->userdata('lastname');
		$remarks = "Petshop Order";

		// Get current domain for redirect URLs
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
		$domain = $protocol . $_SERVER['HTTP_HOST'];

		// Prepare PayMaya API request payload
		$payload = [
			"totalAmount" => [
				"value" => number_format($amount, 2, '.', ''),
				"currency" => "PHP"
			],
			"redirectUrl" => [
				"success" => $domain . "/payment_success.php?from_paymaya=1",
				"failure" => $domain . "/payment_failed.php?from_paymaya=1",
				"cancel" => $domain . "/payment_failed.php?from_paymaya=1"
			],
			"requestReferenceNumber" => uniqid("order_"),
			"description" => $remarks
		];

		$options = [
			'http' => [
				'header'  => "Content-type: application/json\r\n" .
				"Authorization: Basic " . base64_encode("pk-rpwb5YR6EfnKiMsldZqY4hgpvJjuy8hhxW2bVAAiz2N:sk-6s9dwnYGFJdZOYu1HCUAfUZctWEf9AjtHIG38kezX8W") . "\r\n",
				'method'  => 'POST',
				'content' => json_encode($payload),
				'timeout' => 30
			]
		];

		$context  = stream_context_create($options);
		$response = @file_get_contents("https://pg-sandbox.paymaya.com/checkout/v1/checkouts", false, $context);

		if ($response === FALSE) {
			return json_encode(['success' => false, 'error' => 'HTTP request failed']);
		}

		$decoded = json_decode($response, true);

		// Check HTTP response code from headers
		$httpCode = 0;
		if (isset($http_response_header) && is_array($http_response_header)) {
			foreach ($http_response_header as $header) {
				if (preg_match('#HTTP/\d+\.\d+\s+(\d+)#', $header, $matches)) {
					$httpCode = intval($matches[1]);
					break;
				}
			}
		}

		if ($httpCode === 201 && isset($decoded['redirectUrl'])) {
			$checkoutUrl = $decoded['redirectUrl'];
			return json_encode(['success' => true, 'checkout_url' => $checkoutUrl]);
		} else {
			$errorMsg = isset($decoded['message']) ? $decoded['message'] : 'Unknown error';
			return json_encode(['success' => false, 'error' => $errorMsg]);
		}
	}
	
	public function set_session_order() {
		extract($_POST);
		if(isset($order_id)) {
			$_SESSION['order_id'] = $order_id;
			return json_encode(['status' => 'success']);
		}
		return json_encode(['status' => 'failed']);
	}

	// Cancel PayMongo orders unpaid for more than 30 minutes
	public function cancel_unpaid_paymongo_orders() {
		$threshold = date('Y-m-d H:i:s', strtotime('-30 minutes'));
		$sql = "UPDATE `orders` SET `status` = 'cancelled' WHERE `payment_method` = 'paymongo' AND `paid` = 0 AND `created_at` <= '{$threshold}' AND `status` != 'cancelled'";
		$update = $this->conn->query($sql);
		if($update){
			return json_encode(['status' => 'success', 'message' => 'Unpaid PayMongo orders older than 30 minutes have been cancelled.']);
		}else{
			return json_encode(['status' => 'failed', 'error' => $this->conn->error]);
		}
	}

	// Inventory tracking functions
	public function capture_inventory_snapshot() {
		// Capture daily inventory snapshot
		$today = date('Y-m-d');
		
		// Check if snapshot already exists for today
		$check = $this->conn->query("SELECT COUNT(*) as count FROM inventory_history WHERE date_recorded = '{$today}'");
		if($check->fetch_assoc()['count'] > 0) {
			return json_encode(['status' => 'info', 'message' => 'Snapshot already exists for today']);
		}
		
		// Insert current inventory state
		$sql = "INSERT INTO inventory_history (product_id, product_name, quantity, unit, price, date_recorded)
				SELECT 
					i.product_id,
					p.product_name,
					i.quantity,
					i.unit,
					i.price,
					CURDATE()
				FROM inventory i
				JOIN products p ON i.product_id = p.id";
		
		$insert = $this->conn->query($sql);
		if($insert) {
			// Log stock out products
			$this->log_stock_out_products();
			return json_encode(['status' => 'success', 'message' => 'Daily inventory snapshot captured']);
		} else {
			return json_encode(['status' => 'failed', 'error' => $this->conn->error]);
		}
	}

	private function log_stock_out_products() {
		// Log products that just went out of stock
		$today = date('Y-m-d');
		
		// Get products that are out of stock and not already logged
		$stock_out_products = $this->conn->query("
			SELECT i.product_id, p.product_name, i.quantity
			FROM inventory i
			JOIN products p ON i.product_id = p.id
			WHERE i.quantity <= 0 
			AND NOT EXISTS (
				SELECT 1 FROM stock_out_log 
				WHERE product_id = i.product_id 
				AND restocked = 0
			)
		");
		
		while($product = $stock_out_products->fetch_assoc()) {
			$this->conn->query("
				INSERT INTO stock_out_log (product_id, product_name, last_quantity, stock_out_date)
				VALUES ({$product['product_id']}, '{$product['product_name']}', {$product['quantity']}, '{$today}')
			");
		}
	}

	public function get_stock_out_details() {
		$stock_out = $this->conn->query("
			SELECT 
				i.id as inventory_id,
				p.id as product_id,
				p.product_name,
				i.quantity,
				i.unit,
				i.price,
				i.size,
				p.description,
				COALESCE(sol.stock_out_date, CURDATE()) as stock_out_date,
				COALESCE(sol.stock_out_time, NOW()) as stock_out_time
			FROM inventory i
			JOIN products p ON i.product_id = p.id
			LEFT JOIN stock_out_log sol ON p.id = sol.product_id AND sol.restocked = 0
			WHERE i.quantity <= 0
			ORDER BY sol.stock_out_time DESC, p.product_name ASC
		");
		
		$data = [];
		while($row = $stock_out->fetch_assoc()) {
			$data[] = $row;
		}
		
		return json_encode(['data' => $data, 'total_count' => count($data)]);
	}

	public function get_daily_inventory_summary($date = null) {
		if(!$date) $date = date('Y-m-d');
		
		$summary = $this->conn->query("
			SELECT 
				ih.product_id,
				ih.product_name,
				ih.quantity,
				ih.unit,
				ih.price,
				(ih.quantity * ih.price) as total_value,
				p.description,
				COALESCE(previous.quantity, 0) as previous_quantity,
				(ih.quantity - COALESCE(previous.quantity, 0)) as change_in_quantity
			FROM inventory_history ih
			JOIN products p ON ih.product_id = p.id
			LEFT JOIN inventory_history previous ON ih.product_id = previous.product_id 
				AND previous.date_recorded = DATE_SUB(ih.date_recorded, INTERVAL 1 DAY)
			WHERE ih.date_recorded = '{$date}'
			ORDER BY ih.product_name ASC
		");
		
		$data = [];
		$total_products = 0;
		$total_value = 0;
		$stock_out_count = 0;
		
		while($row = $summary->fetch_assoc()) {
			$total_products++;
			$total_value += ($row['quantity'] * $row['price']);
			if($row['quantity'] <= 0) $stock_out_count++;
			$data[] = $row;
		}
		
		return json_encode([
			'summary' => [
				'total_products' => $total_products,
				'total_inventory_value' => $total_value,
				'stock_out_count' => $stock_out_count,
				'date' => $date
			],
			'products' => $data
		]);
	}

	public function restock_product() {
		extract($_POST);
		
		if(!isset($product_id) || !isset($quantity) || $quantity <= 0) {
			return json_encode(['status' => 'failed', 'message' => 'Invalid product or quantity']);
		}
		
		// Update inventory
		$update = $this->conn->query("
			UPDATE inventory 
			SET quantity = quantity + {$quantity}, date_updated = NOW()
			WHERE product_id = {$product_id}
		");
		
		if($update) {
			// Mark as restocked in stock_out_log
			$this->conn->query("
				UPDATE stock_out_log 
				SET restocked = 1, restock_date = CURDATE()
				WHERE product_id = {$product_id} AND restocked = 0
			");
			
			return json_encode(['status' => 'success', 'message' => 'Product restocked successfully']);
		} else {
			return json_encode(['status' => 'failed', 'error' => $this->conn->error]);
		}
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_sub_category':
		echo $Master->save_sub_category();
	break;
	case 'delete_sub_category':
		echo $Master->delete_sub_category();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	
	case 'save_inventory':
		echo $Master->save_inventory();
	break;
	case 'delete_inventory':
		echo $Master->delete_inventory();
	break;
	case 'register':
		echo $Master->register();
	break;
	case 'verify_email':
		echo $Master->verify_email();
	break;
	case 'resend_otp':
		echo $Master->resend_otp();
	break;
	case 'add_to_cart':
		echo $Master->add_to_cart();
	break;
	case 'update_cart_qty':
		echo $Master->update_cart_qty();
	break;
	case 'delete_cart':
		echo $Master->delete_cart();
	break;
	case 'empty_cart':
		echo $Master->empty_cart();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'place_order':
		echo $Master->place_order();
	break;
	case 'update_order_status':
		echo $Master->update_order_status();
	break;
	case 'pay_order':
		echo $Master->pay_order();
	break;
	case 'update_account':
		echo $Master->update_account();
	break;
	case 'delete_order':
		echo $Master->delete_order();
	break;
	case 'send_otp':
		echo $Master->send_otp();
	break;
	case 'change_password':
		echo $Master->change_password();
	break;
	case 'send_password_change_otp':
		echo $Master->send_password_change_otp();
		break;
	case 'send_email_change_otp':
		echo $Master->send_email_change_otp();
		break;
	case 'update_paymongo_link':
		echo $Master->update_paymongo_link();
		break;
	default:
		// echo $sysset->index();
		break;
}
