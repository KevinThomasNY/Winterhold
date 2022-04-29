<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Hold</title>
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
    <link rel="stylesheet" href="../../css/home.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
</body>

</html> <?php
session_start();
include ("../db.php");
if (isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
    #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
    
} else {
    header('location:../login.php');
}
$user_id = trim($_POST['user_id']);
$hold_id = trim($_POST['hold_id']);
$date = date("m/d/Y");
try {
    $query = "insert into student_hold (student_id,hold_id,date_added) value ($user_id,$hold_id,'$date')";
    $stmt = $db->prepare($query);
    $stmt->execute();
?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'Hold Added Successfully...',
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
    // header('location:./student_hold.php');
    
}
catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'You cannot add the same type of hold to a student',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back to add hold!',
    }).then(function() {
        window.location = "addHold.php";
    })
</script> <?php
}
?>