<?php 
session_start();

include("../db.php");

if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:../login.php');
}

$user_id = trim($_GET['id']);
$hold_type = trim($_GET['hold']);

try {
	$query = "delete student_hold from student_hold
	inner join hold on hold.hold_id = student_hold.hold_id
	where student_hold.student_id = " . $user_id . " and hold.hold_type = '" .$hold_type."' ;";
	$stmt = $db->prepare($query); 
	$stmt->execute();

	header('location:./student_hold.php');
} catch (PDOException $e) {
	echo "Error : ".$e->getMessage();
}
?>