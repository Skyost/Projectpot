<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require('include/config.php');
	require('include/functions.php');
	if(isset($_COOKIE['pp_logout'])) {
		delete_cookie('pp_logout');
	}
	else {
		if(isset($_POST['username'])) {
			setcookie('pp_username', $_POST['username']);
			$_COOKIE['pp_username'] = $_POST['username'];
		}
		if(isset($_POST['password'])) {
			$md5_password = md5($_POST['password']);
			setcookie('pp_password', $md5_password);
			$_COOKIE['pp_password'] = $md5_password;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin Area</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="generator" content="<?=PP_APP_NAME?>">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/global.css">
		<link rel="icon" href="assets/favicon.ico" type="image/x-icon">
		<!--[if IE]><link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon"/><![endif]-->
	</head>
	<body>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?php
	if(isset($_COOKIE['pp_username']) && isset($_COOKIE['pp_password'])) {
		if($_COOKIE['pp_username'] == PP_ADMIN_USERNAME && $_COOKIE['pp_password'] == PP_ADMIN_PASSWORD) {
			include('include/adminarea.php');
		}
		else {
			echo message('Wrong username or password !', 'alert-danger');
			delete_cookie('pp_username');
			delete_cookie('pp_password');
			include('include/loginform.php');
		}
	}
	else {
		include('include/loginform.php');
	}
?>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>