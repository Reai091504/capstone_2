<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 

$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI'];

// Check if user is not logged in and trying to access a protected page
if(!isset($_SESSION['userdata']) && !strpos($link, 'login.php') && !strpos($link, 'registration.php')){
    redirect('login.php');
}

// Check if user is logged in and trying to access login/registration pages
if(isset($_SESSION['userdata']) && (strpos($link, 'login.php') || strpos($link, 'registration.php'))){
    redirect('index.php');
}
