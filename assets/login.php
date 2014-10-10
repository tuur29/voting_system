<?php

ob_start();
session_start();
include_once("../options.php");

if (!isset($_POST["logout"])) {
	if (isset($_POST["pass"]) && $_POST["pass"]==$pass) {
		session_regenerate_id();
		$_SESSION['loggedin'] = true;
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
<script>window.location = document.referrer;</script>";
ob_flush();

?>