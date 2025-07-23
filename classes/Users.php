<?php
require_once('../config.php');
require_once('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password) && !empty($id)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']))
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
		}
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully saved.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						$this->settings->set_userdata($k,$v);
					}
				}
				return 1;
			}else{
				return 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						$this->settings->set_userdata($k,$v);
					}
				}
				if(isset($fname) && isset($move))
				$this->settings->set_userdata('avatar',$fname);

				return 1;
			}else{
				return "UPDATE users set $data where id = {$id}";
			}
			
		}
	}
	public function delete_users(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			return 1;
		}else{
			return false;
		}
	}
	public function save_fusers(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','password'))){
				if(!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}

			if(!empty($password))
			$data .= ", `password` = '".md5($password)."' ";
		
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']))
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
			}
			$sql = "UPDATE faculty set {$data} where id = $id";
			$save = $this->conn->query($sql);

			if($save){
			$this->settings->set_flashdata('success','User Details successfully updated.');
			foreach($_POST as $k => $v){
				if(!in_array($k,array('id','password'))){
					if(!empty($data)) $data .=" , ";
					$this->settings->set_userdata($k,$v);
				}
			}
			if(isset($fname) && isset($move))
			$this->settings->set_userdata('avatar',$fname);
			return 1;
			}else{
				$resp['error'] = $sql;
				return json_encode($resp);
			}

	} 

	public function save_susers(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','password'))){
				if(!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}

			if(!empty($password))
			$data .= ", `password` = '".md5($password)."' ";
		
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']))
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
			}
			$sql = "UPDATE students set {$data} where id = $id";
			$save = $this->conn->query($sql);

			if($save){
			$this->settings->set_flashdata('success','User Details successfully updated.');
			foreach($_POST as $k => $v){
				if(!in_array($k,array('id','password'))){
					if(!empty($data)) $data .=" , ";
					$this->settings->set_userdata($k,$v);
				}
			}
			if(isset($fname) && isset($move))
			$this->settings->set_userdata('avatar',$fname);
			return 1;
			}else{
				$resp['error'] = $sql;
				return json_encode($resp);
			}

	} 
	
	public function recover_password(){
		extract($_POST);
		
		// Check if email exists
		$qry = $this->conn->query("SELECT * FROM clients where email = '$email'");
		if($qry->num_rows > 0){
			$row = $qry->fetch_assoc();
			
			// Generate a random password
			$new_password = substr(md5(rand()), 0, 8);
			$hashed_password = md5($new_password);
			
			// Update the password in database
			$update = $this->conn->query("UPDATE clients set password = '$hashed_password' where email = '$email'");
			
			if($update){
				// Create a new PHPMailer instance
				$mail = new PHPMailer(true);

				try {
					// Server settings
					$mail->isSMTP();
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Username = 'bxaiglenn@gmail.com'; // Replace with your Gmail
					$mail->Password = 'owqkpodtcohiohhv'; // Replace with your app password
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
					$mail->Port = 587;

					// Recipients
					$mail->setFrom('bxaiglenn@gmail.com', 'Pet Shop');
					$mail->addAddress($email, $row['firstname']);

					// Content
					$mail->isHTML(true);
					$mail->Subject = 'Password Recovery - Pet Shop';
					
					// HTML email content
					$mail->Body = "
					<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
						<div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
							<h2 style='color: #2c3e50;'>Password Recovery</h2>
							<p>Hello ".$row['firstname'].",</p>
							<p>Your password has been reset. Here is your new password:</p>
							<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
								<strong>Password: ".$new_password."</strong>
							</div>
							<p><strong>Important:</strong> Please login with this password and change it immediately for security reasons.</p>
							<p>If you did not request this password reset, please contact our support team immediately.</p>
							<hr style='border: 1px solid #eee; margin: 20px 0;'>
							<p style='color: #666; font-size: 0.9em;'>Best regards,<br>Pet Shop Team</p>
						</div>
					</div>";

					$mail->send();
					$resp['status'] = 'success';
					$resp['msg'] = "Password recovery instructions have been sent to your email.";
				} catch (Exception $e) {
					$resp['status'] = 'failed';
					$resp['msg'] = "Failed to send email. Error: {$mail->ErrorInfo}";
				}
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Failed to update password. Please try again later.";
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "Email not found in our records.";
		}
		
		return json_encode($resp);
	}
	public function send_login_otp() {
		extract($_POST);
		$qry = $this->conn->query("SELECT * FROM clients WHERE email = '$email'");
		if($qry->num_rows > 0){
			$otp = sprintf("%06d", mt_rand(1, 999999));
			$_SESSION['login_otp'] = $otp;
			$_SESSION['login_otp_email'] = $email;
			$_SESSION['login_otp_expiry'] = time() + 600; // 10 minutes
			// Send OTP via email
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'bxaiglenn@gmail.com';
				$mail->Password = 'yvyyvognuaikmfja';
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port = 587;
				$mail->setFrom('bxaiglenn@gmail.com', 'Pet Shop');
				$mail->addAddress($email);
				$mail->isHTML(true);
				$mail->Subject = 'Login OTP - Pet Shop';
				$mail->Body = "<p>Your OTP for login is: <strong>$otp</strong></p><p>This OTP is valid for 10 minutes.</p>";
				$mail->send();
				return json_encode(['status' => 'success', 'msg' => 'OTP sent to your email.']);
			} catch (Exception $e) {
				return json_encode(['status' => 'failed', 'msg' => 'Failed to send OTP.']);
			}
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'Email not found in our records.']);
		}
	}
	public function verify_login_otp() {
		extract($_POST);
		if (!isset($_SESSION['login_otp'], $_SESSION['login_otp_email'], $_SESSION['login_otp_expiry'])) {
			return json_encode(['status' => 'failed', 'msg' => 'No OTP session found.']);
		}
		if (time() > $_SESSION['login_otp_expiry']) {
			return json_encode(['status' => 'failed', 'msg' => 'OTP expired.']);
		}
		if ($otp != $_SESSION['login_otp'] || $email != $_SESSION['login_otp_email']) {
			return json_encode(['status' => 'failed', 'msg' => 'Invalid OTP or email.']);
		}
		// Log in the user
		$qry = $this->conn->query("SELECT * FROM clients WHERE email = '$email'");
		if($qry->num_rows > 0){
			$row = $qry->fetch_assoc();
			foreach($row as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
			}
			$this->settings->set_userdata('login_type',2);
			unset($_SESSION['login_otp'], $_SESSION['login_otp_email'], $_SESSION['login_otp_expiry']);
			return json_encode(['status' => 'success']);
		} else {
			return json_encode(['status' => 'failed', 'msg' => 'User not found.']);
		}
	}
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'fsave':
		echo $users->save_fusers();
	break;
	case 'ssave':
		echo $users->save_susers();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'recover':
		echo $users->recover_password();
	break;
	case 'send_login_otp':
		echo $users->send_login_otp();
	break;
	case 'verify_login_otp':
		echo $users->verify_login_otp();
	break;
	default:
		// echo $sysset->index();
		break;
}