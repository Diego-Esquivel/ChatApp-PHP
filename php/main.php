<!--***************************************************************************************************************************
*   main.php : This file contains the 'login' view and functionalities. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show a login view to the web app. 
****************************************************************************************************************************-->

<?php 
	include('functions.php') ;
	require_once('../inc/chat.inc.php');
	
	$oSimpleChat = new SimpleChat();
	// draw chat application
	$sChatResult = 'Need login before using';

    if (isLoggedIn()){
		header('location: user_home_pagina.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Login</h2>
	</div>
	<form method="post" action="main.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" >
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="login_btn">Login</button>
		</div>
		<p>
			Not yet a member? <a href="../index.php">Sign up</a>
		</p>
	</form>
</body>
</html>