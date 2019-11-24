<?php 
include 'config.php';
session_start();
$username = $_POST['username'];
$password = md5($_POST['password']);
 

$login = mysqli_query($db, "select * from admin where username='$username' and password='$password'");
$cek = mysqli_num_rows($login);
if($cek > 0){
	$_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
	header("location:menu.html");
}else{
    header("location:login.html");	
}
?>