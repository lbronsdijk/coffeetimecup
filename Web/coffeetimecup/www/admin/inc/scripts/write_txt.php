<?php
		
	$post = $_POST['temp'];

	$content = "USERID:5;SSID:;PASSWORD:;NEW_DEGREE:" . $post . ";LAST_DEGREE:0;";
	$file = "../../mug config/coffee.txt";
	$handle = fopen($file,"w") or die("can't open file");
	fwrite($handle,$content);
	fclose($handle);
	chmod($file, 0777);
?>