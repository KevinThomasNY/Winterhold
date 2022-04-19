<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Hold</title>
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
$hold_type = trim($_GET['hold']);

try {
	$query = "delete student_hold from student_hold
	inner join hold on hold.hold_id = student_hold.hold_id
	where student_hold.student_id = " . $user_id . " and hold.hold_type = '" .$hold_type."' ;";
	$stmt = $db->prepare($query); 
	$stmt->execute();
			?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'Hold Removed Successfully...',
        allowOutsideClick: false,
        icon: "success",
        html: 'I will close in <b></b> milliseconds.',
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
        }
    }).then(function() {
        window.location = "student_hold.php";
    })
</script> <?php

	
} catch (PDOException $e) {
	echo "Error : ".$e->getMessage();
}
?>