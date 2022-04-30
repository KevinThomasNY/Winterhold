<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove student From Advisor List</title>
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
$student_id = trim($_GET['id']);
$faculty_id = trim($_GET['faculty_id']);
try {
    $query = "delete from advisor where faculty_id = $faculty_id and student_id = $student_id;";
    $stmt = $db->prepare($query);
    $stmt->execute();
?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'Student Deleted Successfully...',
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
        window.location = "advisors.php";
    })
</script> <?php
    // header('location:./student_hold.php');
    
}
catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'Something went wrong',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back to advisors!',
    }).then(function() {
        window.location = "advisors.php";
    })
</script> <?php
}
?>