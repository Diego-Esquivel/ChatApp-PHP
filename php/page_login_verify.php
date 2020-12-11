<!--***************************************************************************************************************************
*   page_login_verify.php : This file contains the 'login verify' view and functionality calls. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show the a view for making sure of the users being logged in through 
*	discrete means.
****************************************************************************************************************************-->

<?php
	include('functions.php');
	if(!isLoggedIn()){
		//header('location: main.php');
		
		echo "<script>window.location.replace('main.php');
		var x = document.getElementsByTagName('iframe');
		document.getElementsByTagName('brep').innerHTML = x.length;
		var frm = document.createElement('iframe');
		frm.setAttribute('src','main.php');
		frm.setAttribute('id','frm');
		document.body.appendChild(frm);</script>";
	}
	echo "HELLO!!";
	echo "<script>document.body.innerHTML = window.location.href;</script>";
	
?>