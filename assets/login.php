<?php

ob_start();
session_start();
include_once("../defaults.php");

if (!isset($_POST["logout"])) {
	if (isset($_POST["pass"]) && $_POST["pass"]==$pass) {
		session_regenerate_id();
		$id = '0';
		if (is_dir('../'.$folder) && file_exists('../'.$folder."/round.txt")){
			$id = file_get_contents('../'.$folder."/round.txt",null,null,null,10);
		}
		$_SESSION['loggedin'] = $id;
		session_write_close();
	}else {
		//Wrong password
	}
} else {
	session_unset();
	session_destroy();
	//logout successfull!
}

//login successfull!
echo "<script>window.location = document.referrer;</script>";
ob_flush();

?>