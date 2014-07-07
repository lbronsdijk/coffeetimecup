<?php
include("../config.php");
include("../functions.php");

$mug_id = escape_string($_GET["mug"]);
$user_id = escape_string($_GET["user"]);

$file = $mug_id . ".txt";

$handle = fopen("../../mug config/" . $file, "r");
$Data = fgets($handle);

preg_match('/USERID:(.*?);/s', $Data, $UserMatches);
preg_match('/NEW_DEGREE:(.*?);/s', $Data, $MugMatches);

$MugUser = $UserMatches[1];
$CurrentDegree = $MugMatches[1];

if($MugUser == $user_id){
	echo round($CurrentDegree, 0);	
}else{
	echo "This mug doesn't belong to this user.";
}
?>