<?php include "db.php" ?>
<?php session_start(); ?>
<?php
	$_SESSION['username'] = null;
	$_SESSION['user_firstname'] = null;
	$_SESSION['user_lastname'] = null;
	$_SESSION['user_role'] = null;
	$_SESSION['user_id'] = null;
    $_SESSION['user_email'] = null;
    $_SESSION['user_phone'] = null;
    $_SESSION['user_address'] = null;

	header("Location: ../home")
?>