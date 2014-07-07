<?php
	//Escape the incomming string, prevent sql injection hack
	function escape_string($var){
		global $mysqli;
		
		return $mysqli->real_escape_string($var);
	}
	
	//Login function
	function login($email, $password){
		global $mysqli;
		global $GLOBAL_user_ip;
		
		//encrypt the password
		$password = sha1(md5($password));
		
		$login_query = $mysqli->query("SELECT * FROM users WHERE email = '" . $email . "' AND password = '" . $password . "' LIMIT 1");
		$login_count = $login_query->num_rows;
		
		
		if($login_count == 1){
			$login_row = $login_query->fetch_object();
			
			$session_id = md5(uniqid());
			$_SESSION["login_session"] = $session_id;
			$_SESSION["user_id"] = $login_row->id;
			
			$mysqli->query("UPDATE `users` SET `login_session` = '" . $session_id . "', ip = '" . $GLOBAL_user_ip . "' WHERE `email` = '" . $email . "'");

			header("Location: ".$_SERVER["REQUEST_URI"]);
		}else{
			echo "<div class='error'>Your email or password is incorrect! Please try it again.</div>";
		}
	}
	
	//Register account
	function register($email, $password, $firstname, $lastname){
		global $mysqli;
		
		$register_query = $mysqli->query("SELECT * FROM users WHERE email = '" . $email . "'");
		$register_count = $register_query->num_rows;
		
		if($register_count == 0){
			$password = sha1(md5($password));
			
			$mysqli->query("INSERT INTO `users` VALUES (NULL , '" . $email . "', '" . $password . "', '0', '" . $firstname . "', '" . $lastname . "', '', '')");
			
			echo "<div class='approved'>You've successfully register your account, you can now login</div>";
		}else{
			echo "<div class='error'>This email address is already in use!</div>";
		}
	}
	
	//Logout the user
	function logout(){
		unset($_SESSION["login_session"]);
		unset($_SESSION["user_id"]);
		header("Location: /admin");
	}
	
	//Check the user is logged in into multiple system
	function check_login(){
		global $mysqli;						
		$session_query = $mysqli->query("SELECT * FROM users WHERE id =" . $_SESSION["user_id"]);
		$session_row = $session_query->fetch_object();
			
		$user_session = $session_row->login_session;
		
		if($_SESSION["login_session"] != $user_session){
			echo "<div class='error'>You've logged into an another system, please relog.</div>";
			unset($_SESSION["login_session"]);
			header("Refresh:5; url=/admin");
		}
	}
	
	//Add a new mug to an account
	function add_mug($serial){
		global $mysqli;
		global $GLOBAL_user_id;
		global $GLOBAL_user_mug_count;

		if($serial != ""){
            if($GLOBAL_user_mug_count == 0){
                $default = 1;
            }else{
                $default = 0;
            }

			$mysqli->query("INSERT INTO `mugs`VALUES (NULL , '" . $GLOBAL_user_id . "', '" . $serial . "', '" . $serial . "', '#d7cab5', '" . $default . "')");
			
			$content = "USERID:" . $GLOBAL_user_id . ";SSID:;PASSWORD:;NEW_DEGREE:0;LAST_DEGREE:0;";
			$file = "mug config/" . $serial . ".txt";
			$handle = fopen($file,"w");
			fwrite($handle,$content);
			fclose($handle);
			chmod($file, 0777);
			
			echo "<div class='approved'>New mug has been added to your account!</div>";
		}
	}
	
	//Get the current degree of a mug
	function get_degree($mug_serial, $user_id){
		print file_get_contents("http://local.coffeetimecup/admin/inc/scripts/get_degree.php?mug=" . $mug_serial . "&user=" . $user_id);
	}
?>