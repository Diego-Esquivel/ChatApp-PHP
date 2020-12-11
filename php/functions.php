<!--***************************************************************************************************************************
*   functions.php : This file contains the 'functions' for the majority of the web app. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to do just about everything in the web app.!!
****************************************************************************************************************************-->

<?php 
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'my_simple_chat');

// variable declaration
$username = "";
//$email    = "";
$add_contact = "";
$errors   = array(); 
$name_error;

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username;//, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	//$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	/*if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}*/
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO s_chat_users (username, user_type, password) 
					  VALUES('$username', '$user_type', '$password')"; //'email' + '$email' existed once respectively  in users AND VALUES field after(post) respective username referenceing
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO sign_up (username, user_type, password) 
					  VALUES('$username', 'user', '$password')"; //'email' + '$email' existed once respectively  in users AND VALUES field after(post) respective username referenceing
			mysqli_query($db, $query);
			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);
			
			$query = "INSERT INTO login (username, user_type, password ,userID) 
					  VALUES('$username', 'user', '$password' ,(SELECT userID FROM sign_up WHERE username = '$username'))";
			mysqli_query($db, $query);
			
			$query = "INSERT INTO contacts (username, userID, id ,`when`) 
					  VALUES((SELECT username FROM login WHERE userID = '0'), (SELECT userID FROM sign_up WHERE username = '$username'), '999' ,UNIX_TIMESTAMP())";
			mysqli_query($db, $query);
			
			$query = "INSERT INTO conversations (userID, id, contact_name, `when`)
						VALUES((SELECT userID FROM login WHERE username = '$username'), 0, 'guest', UNIX_TIMESTAMP())";
			mysqli_query($db, $query);
			
			$query = "INSERT INTO conversations (userID, id, contact_name, `when`)
						VALUES((SELECT userID FROM login WHERE username = 'guest'), (SELECT userID FROM sign_up WHERE username = '$username'), '$username', UNIX_TIMESTAMP())";
			mysqli_query($db, $query);
			
			$startMessage = "hi!";
			$query = "INSERT INTO conversation (message, user, cid, id, `when`)
						VALUES ('Hi!', 'guest', '$logged_in_user_id', 0, UNIX_TIMESTAMP())";
			mysqli_query($db, $query);
			
			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			simple_login($username ,$password);
			header('location: user_home_pagina.php');				
		}
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM login WHERE userID=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}	

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
	simple_logout();
	mysqli_close($db);
	session_destroy();
	unset($_SESSION['user']);
	header("location: main.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM login WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			simple_login($username ,$password);
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') 
			{
				$_SESSION['user'] = $logged_in_user['user'];
				$_SESSION['success']  = "You are now logged in";
				header('location: home.php');		  
			}else
			{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				
				header('location: user_home_pagina.php');
			}
		}else 
		{
			array_push($errors, "Wrong username/password combination");
		}
	}
}

// ...
function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

function simple_login($sName, $sPass) {
        simple_logout();

        $sMd5Password = MD5($sPass);

        $iCookieTime = time() + 24*60*60*30;
        setcookie("member_name", $sName, $iCookieTime, '/');
        $_COOKIE['member_name'] = $sName;
        setcookie("member_pass", $sMd5Password, $iCookieTime, '/');
        $_COOKIE['member_pass'] = $sMd5Password;
}
	
function simple_logout() { 
    setcookie('member_name', '', time() - 96 * 3600, '/');
    setcookie('member_pass', '', time() - 96 * 3600, '/');

    unset($_COOKIE['member_name']);
    unset($_COOKIE['member_pass']);
}

if (isset($_POST['add_btn'])) {
	add_user();
}

function add_user(){
	global $db, $username, $errors, $name_error;
$username = e($_COOKIE['member_name']);
	// grap form values
	$add_contact = e($_POST['add_contact']);
	$sql_u = "SELECT * FROM sign_up WHERE username='$add_contact'";
	$res_u = mysqli_query($db, $sql_u);
	$sql_c = "SELECT * FROM contacts WHERE username='$add_contact' AND userID = (SELECT userID FROM sign_up WHERE username = '$username')";
	$res_c = mysqli_query($db, $sql_c);
	if (mysqli_num_rows($res_u) === 1 && mysqli_num_rows($res_c) === 0 && $username !==  $add_contact) {
	$query = "INSERT INTO contacts (username, userID, id ,`when`) 
					  VALUES((SELECT username FROM login WHERE username = '$add_contact'), (SELECT userID FROM sign_up WHERE username = '$username'), '999' ,UNIX_TIMESTAMP())";
			mysqli_query($db, $query);
		echo "<script>window.location.href('page2.html');</script>";
	} else{
		$name_error = "Sorry name doesn't exist;";
	}
}


function setdetailfunction($cold_){
	$_SESSION['link'] = $cold_;
}

function checkissetdetail($check_){
	if(isset($_SESSION[$check_])){
		return true;
	}
	else{
		return false;
	}
}