<!--***************************************************************************************************************************
*   user_home_pagina.php : This file contains the 'user_home_pagina' view and connects to functionalities. 
*   Author: Diego Esquivel
*   Date: 05/03/2020 V1.5.03 
*   Purpose: The purpose of the file is to properly show a home view for a typical user. In here we also provide a
*	post means to select users to open chat with.
****************************************************************************************************************************-->

<?php 
	include('functions.php');
	include('inc/chat.inc.php');

	if(!isLoggedIn()){
		$_SESSION['msg'] = "You must log in first";
		header('location: main.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (!isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['msg']; 
						unset($_SESSION['msg']);
					?>
				</h3>
			</div>
		<?php endif ?>
		<!-- logged in user information -->
		<div class="profile_info">
			<img src="images/user_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="user_home_pagina.php?logout='1'" style="color: red;">logout</a>
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
</body>
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
		  $iCookieTime = time() + 24*60*60*30;
			$_SESSION["count"] = $count;
			
		if(isset($_GET['link'])){
			setdetailfunction($_GET['link']);
			echo $_GET['link'];
			header('location: page2.html');
			//echo "PIE";
		}
	?>
	
    </ul>
	<form method="post" action="user_home_pagina.php" id="add_recipient">
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