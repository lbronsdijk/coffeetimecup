<?php
include("../config.php");
include("../functions.php");

echo $mug_serial = escape_string($_POST["serial"]);
echo $userid = escape_string($_POST["userid"]);

$mug_info_query = $mysqli->query("SELECT * FROM mugs WHERE mug_serial = '" . $mug_serial . "'");
$mug_info_row = $mug_info_query->fetch_object();

if($mug_info_row->user_id == $userid){
	$mysqli->query("UPDATE `mugs` SET `default` = '0' WHERE `user_id` = '" . $userid . "' AND `default` = '1'");
	$mysqli->query("UPDATE `mugs` SET `default` = '1' WHERE `mug_serial` = '" . $mug_serial . "'");
}
?>