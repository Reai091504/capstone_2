<?php
// Merged from initialize.php
if(!defined('base_url')) define('base_url','https://petshopoyeee.com/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );

if(!defined('DB_SERVER')) define('DB_SERVER','localhost');
if(!defined('DB_USERNAME')) define('DB_USERNAME','enlmvnqs_petshop_user');
if(!defined('DB_PASSWORD')) define('DB_PASSWORD','574jTQ{7[wShF[M@');
if(!defined('DB_NAME')) define('DB_NAME','enlmvnqs_petshop');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
// ... other config

session_start();
error_reporting(E_ALL);

require_once('classes/DBConnection.php');
require_once('classes/SystemSettings.php');
$db = new DBConnection;
$conn = $db->conn;

function redirect($url=''){
	if(!empty($url))
	echo '<script>location.href="'.base_url .$url.'"</script>';
}
function validate_image($file){
	if(!empty($file)){
			// exit;
		if(is_file(base_app.$file)){
			return base_url.$file;
		}else{
			return base_url.'uploads/no-image-available.png';
		}
	}else{
		return base_url.'uploads/no-image-available.png';
	}
}
function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..  
    return false;
}

function end_load() {
    // You can add code here to hide a loading spinner if you have one.
    // For now, this empty function will prevent the error.
}
?>