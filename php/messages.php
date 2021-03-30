<!--***************************************************************************************************************************
*   messages.php : This file contains the 'message' view and functionalities. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show the message view of the web app. so here we start the chat and call
*	 to get messages and display.
****************************************************************************************************************************-->

<meta http-equiv="refresh" content="4">
<?php
	include('functions.php');
	require_once('../inc/chat.inc.php');

	$oSimpleChat = new SimpleChat();
	if(checkissetdetail('link')){
		echo "GOODBYE!!";
		
		echo $oSimpleChat->getMessages(e($_SESSION['link']));
	}
	else{
		echo "HELLO!!!";
	}
?>