<?php
session_start();
$_SESSION['sess_user_id'] = "";
$_SESSION['sess_username'] = "";
$_SESSION['sess_name'] = "";
$_SESSION['sess_user_type'] = "";
if(empty($_SESSION['sess_user_id'])) header("location: login.php");
?>