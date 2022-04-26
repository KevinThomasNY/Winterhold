<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Confrim</title>
    <link rel="stylesheet" href="../../css/home.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
</body>

</html> <?php 
session_start();

include("../db.php");

if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:../login.php');
}

$user_id = trim($_GET['id']);

try {
    $query = "delete from user where user_id = $user_id";
	$stmt = $db->prepare($query); 
	$stmt->execute();
	
    header('location:./view_all_users.php');

	
} catch (PDOException $e) {
	echo "Error : ".$e->getMessage();
}
?>