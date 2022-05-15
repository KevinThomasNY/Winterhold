<!-- This file drops a student from Fall 2022 schedule -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Class</title>
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
$crn = $_GET['crn'];
$student_id = $_GET['id'];
try {
    #Delete from student_history
    $query = "DELETE From student_history where student_id = '$student_id' and crn = '$crn'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    #Add seat to class_section
    $result = $db->query("select available_seats from class_section where crn = '$crn'");
    while ($rows = $result->fetch()){
        $seats = $rows['available_seats'];
    }
    $seats = $seats + 1;
    #Execute query
    $query = "update class_section set available_seats = '$seats' where crn = '$crn'";
    $stmt = $db->prepare($query);
    $stmt->execute();
?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'Student Dropped From Class Successfully...',
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
        window.location = "view_students.php";
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
        confirmButtonText: 'Take me back!',
    }).then(function() {
        window.location = "view_students.php";
    })
</script> <?php
}
?>