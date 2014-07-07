<?php
include("../config.php");
include("../functions.php");

$mug_serial = escape_string($_POST["serial"]);
$user_id = escape_string($_POST["userid"]);
$mug_color = escape_string($_POST["color"]);

$mug_info_query = $mysqli->query("SELECT * FROM mugs WHERE mug_serial = '" . $mug_serial . "'");
$mug_info_row = $mug_info_query->fetch_object();

if($mug_info_row->user_id == $user_id){
    $mysqli->query("UPDATE `mugs` SET `mug_color` = '" . $mug_color . "' WHERE `mug_serial` = '" . $mug_serial . "'");
}
?>