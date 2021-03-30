<!--***************************************************************************************************************************
*   index.php : This file contains the 'index' view and functionalities. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show a registration view.
****************************************************************************************************************************-->

<?php 
	include('php/functions.php') ;
	include('inc/chat.inc.php');

	// draw chat application
	$sChatResult = 'Need login before using';
	
    if (isLoggedIn()){
		header('location: php/main.php');
    }
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Registration system PHP and MySQL</title>
	</head>
	<body>
		<div class="header">
			<h2>Register</h2>
		</div>
		<form method="post" action="index.php">
			<?php echo display_error(); ?>
			<div class="input-group">
				<label>Username</label>
				<input type="text" name="username" value="">
			</div>
			<!--<div class="input-group">
				<label>Email</label>
				<input type="email" name="email" value="">
			</div>-->
			<div class="input-group">
				<label>Password</label>
				<input type="password" name="password_1">
			</div>
			<div class="input-group">
				<label>Confirm password</label>
				<input type="password" name="password_2">
			</div>
			<div class="input-group">
				<button type="submit" class="btn" name="register_btn">Register</button>
			</div>
			<p>
				Already a member? <a href="php/main.php">Sign in</a>
			</p>
		</form>
	</body>
</html>