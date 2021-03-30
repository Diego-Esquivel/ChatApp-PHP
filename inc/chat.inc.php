<?php
// simple chat class
	class SimpleChat {
		// DB variables
		var $sDbName;
		var $sDbUser;
		var $sDbPass;

		var $username;
		var $cusername;
		
		// constructor
		function SimpleChat() {
			$this->sDbName = 'my_simple_chat';
			$this->sDbUser = 'root';
			$this->sDbPass = '';
			//session_start();
		}

		// adding to DB table posted message
		function acceptMessages() {
			if (isset($_COOKIE['member_name'])) {
				$this->username = e($_COOKIE['member_name']);
				$username = e($_COOKIE['member_name']);
				//echo $username;
				if(isset($_POST['s_say']) && $_POST['message']) {
					$sUsername = $_COOKIE['member_name'];

					//the host, name, and password for your mysql
					$vLink = mysqli_connect("localhost", $this->sDbUser, $this->sDbPass, $this->sDbName);

					//select the database
					mysqli_select_db($vLink, $this->sDbName);

					$sMessage = mysqli_real_escape_string($vLink, $_POST['message']);
					if ($sMessage != '') {
						$cusername = $_SESSION['link'];
						//echo $cusername . "Potpie" . $sMessage;
						mysqli_query($vLink, "INSERT INTO conversation (message, user, cid, id, `when`)
							VALUES ('$sMessage', '$username', (SELECT userID FROM sign_up WHERE username = '$cusername'), (SELECT id FROM conversations WHERE contact_name = '$username'), UNIX_TIMESTAMP())");
					}

					mysqli_close($vLink);
				}
			}

			ob_start();
			require_once('../html/chat_input.html');
			$sShoutboxForm = ob_get_clean();

			return $sShoutboxForm;
		}
		
		
		function getMessages($linkDet) {
			$username = $_COOKIE['member_name'];
			$vLink = mysqli_connect("localhost", $this->sDbUser, $this->sDbPass, $this->sDbName);
			$_SESSION['link'] = $linkDet;
			//select the database
			mysqli_select_db($vLink, $this->sDbName);
			$iCookieTime = time() + 24*60*60*30;
			$cusername = $linkDet;
			//returning the last 15 messages
			$vRes = mysqli_query($vLink, "SELECT * FROM `conversation` WHERE (cid = (SELECT userID FROM sign_up WHERE username = '$username' LIMIT 1)) AND (id = (SELECT id FROM conversations WHERE contact_name = '$cusername' LIMIT 1))
											UNION
											SELECT * FROM `conversation` WHERE (cid = (SELECT userID FROM sign_up WHERE username = '$cusername' LIMIT 1)) AND (id =(SELECT id FROM conversations WHERE contact_name = '$username' LIMIT 1))
											ORDER BY `when` DESC LIMIT 15");

			$sMessages = '';
			
			// collecting list of messages
			if ($vRes) {
				while($aMessages = mysqli_fetch_array($vRes)) {
					$sWhen = date("H:i:s", $aMessages['when']);
					$sMessages .= '<div class="message">' . $aMessages['user'] . ': ' . $aMessages['message'] . '<span>(' . $sWhen . ')</span></div>';
				}
			} else {
				$sMessages = 'DB error, create SQL table before';
			}

			mysqli_close($vLink);

			ob_start();
			require_once('../html/chat_begin.html');
			echo $sMessages;
			require_once('../html/chat_end.html');
			return ob_get_clean();
		}
	}
?>