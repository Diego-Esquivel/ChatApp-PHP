<!--***************************************************************************************************************************
*   teams.php : This file contains the 'contacts' view and call to functionalities. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show the cantacts of a user and includes means to post/send a message to
*	the other client.
****************************************************************************************************************************-->

<?php 
	include('functions.php');
	include('inc/chat.inc.php');

	$oSimpleChat = new SimpleChat();

	if (isLoggedIn()){
		$sChatResult = $oSimpleChat->acceptMessages();
	}else{
		header('location: main.php');
	}
	echo $sChatResult;
?>

<html>
    <head>
    <title>Retrieve data from the database</title>
    </head>
    <body>

    <ul>

    <?php

		// SQL query
		$username = mysqli_real_escape_string($db, trim($_COOKIE['member_name']));
		$strSQL = "SELECT * FROM contacts WHERE userID = (SELECT userID FROM login WHERE username = '$username') ORDER BY userID DESC";

		// Execute the query (the recordset $rs contains the result)
		$rs = mysqli_query($db ,$strSQL);
		$count = 1;
		// Loop the recordset $rs
		while($row = mysqli_fetch_array($rs)) {
		   // Name of the person
		  $strName = $row['username'];// . " " . $row['LastName'];

		   // Create a link to person.php with the id-value in the URL
		   $strLink = '<a href= "?link=' . $strName .'">' . $strName . '</a>';

			// List link
		   echo "<li>" . $strLink . "</li>";
			$count++;
		}
		$_SESSION["count"] = $count;
			
		if(isset($_GET['link'])){
			setdetailfunction($_GET['link']);
			echo $_GET['link'];
			//echo "PIE";
		}
	?>
	
    </ul>
	<form method="post" action="teams.php" id="add_recipient">
	<h4>Contact</h4>
  	<div <?php if (isset($name_error)): ?> class="form_error" <?php endif ?> >
	  <input type="text" name="add_contact" placeholder="Username" value="<?php echo $add_contact; ?>">
	  <?php if (isset($name_error)): ?>
	  	<span><?php echo $name_error; ?></span>
	  <?php endif ?>
  	</div>
		<div class="input-group">
			<button type="submit" class="btn" name="add_btn">ADD</button>
		</div>
	</form>
    </body>
    </html>