<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		global $_settings;
		$_settings->is_admin = true;
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$_SESSION['admin_userdata'][$k] = $v;
				}
			}
			$_SESSION['admin_userdata']['login_type'] = 1;
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	public function logout(){
		global $_settings;
		$_settings->is_admin = true;
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	function login_user(){
		extract($_POST);
		$email = $this->conn->real_escape_string($email);
		$password = $this->conn->real_escape_string($password);

		$qry = $this->conn->query("SELECT * from clients where email = '$email'");

		if($qry->num_rows > 0){
			$row = $qry->fetch_assoc();
			if(password_verify($password, $row['password'])){
				foreach($row as $k => $v){
					if(!is_numeric($k) && $k != 'password'){
						$this->settings->set_userdata($k,$v);
					}
				}
				$this->settings->set_userdata('login_type',2); // Assuming 2 for client login
				$resp['status'] = 'success';
			} else {
				$resp['status'] = 'incorrect'; // Incorrect password
			}
		}else{
			$resp['status'] = 'incorrect'; // Email not found
		}
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['_error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'login_user':
		echo $auth->login_user();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

