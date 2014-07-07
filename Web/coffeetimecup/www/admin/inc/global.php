<?php
	$GLOBAL_user_ip = $_SERVER['REMOTE_ADDR'];
	
	//Creating user globals
	if(isset($_SESSION["login_session"])){
		$user_query = $mysqli->query("SELECT * FROM users WHERE id =" . $_SESSION["user_id"]);
		$user_row = $user_query->fetch_object();

        $user_mug_query = $mysqli->query("SELECT * FROM mugs WHERE user_id = '" . $_SESSION["user_id"] . "'");

		//User global
		$GLOBAL_user_id = $user_row->id;
		$GLOBAL_user_email = $user_row->email;
		$GLOBAL_user_admin = $user_row->admin;
		$GLOBAL_user_firstname = $user_row->firstname;
		$GLOBAL_user_lastname = $user_row->lastname;
		$GLOBAL_user_db_session = $user_row->login_session;

        //Mug info global
        $GLOBAL_user_mug_count = $user_mug_query->num_rows;
	}

?>