<?php
	session_start();
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin panel</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheet/stylesheet.css">
</head>

<body>
    <div class="wrapper">
    <?php
        include ("inc/config.php");
        include ("inc/global.php");
        include ("inc/functions.php");

        if(isset($_POST["login"])){
            $email = escape_string($_POST["email"]);
            $pass = escape_string($_POST["password"]);

            login($email, $pass);
        }

        if(isset($_POST["register"])){
            $email = escape_string($_POST["register_email"]);
            $pass = escape_string($_POST["register_password"]);
            $firstname = escape_string($_POST["register_firstname"]);
            $lastname = escape_string($_POST["register_lastname"]);

            register($email, $pass, $firstname, $lastname);
        }

        if(isset($_GET["logout"])){
            logout();
        }

        if(isset($_SESSION["login_session"])){
            check_login();
            if(isset($_GET["ToDo"])){
                $page = $_GET["ToDo"];
                include ("inc/snippets/" . $page . ".php");
            }else{
                include ("inc/snippets/home.php");
            }

        }else{
            include ("inc/snippets/login.php");
        }
    ?>
    </div>
    
    <div class="footer">
    </div>
</body>
</html>