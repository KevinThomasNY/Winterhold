<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
    <link rel="stylesheet" href="../../css/home.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
</body>

</html> <?php
session_start();
include ("../db.php");
if (isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "")
{
    #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
    
}
else
{
    header('location:../login.php');
}
$errorMsg = "";
# Get data to input
$student_id = $_POST['student_id'];
$first_name = filter_input(INPUT_POST, "first_name");
$last_name = filter_input(INPUT_POST, "last_name");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$address = filter_input(INPUT_POST, "address");
$city = filter_input(INPUT_POST, "city");
$state = filter_input(INPUT_POST, "state");
$zip = filter_input(INPUT_POST, "zip");
$date_of_birth = filter_input(INPUT_POST, "date_of_birth");


    try{
    #Update user table in database
    $query = "UPDATE user SET first_name = '$first_name', last_name = '$last_name', date_of_birth = '$date_of_birth', address = '$address', city = '$city', state = '$state', zip = $zip where user_id = $student_id;";
    $stmt = $db->prepare($query);
    $stmt->execute();
    #Update Login table in database
    $query2 = "UPDATE login SET email = '$email', password = '$password' where user_id = $student_id;";
    $stmt = $db->prepare($query2);
    $stmt->execute();
    #Below is the Javascript success alert message
?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'Info Updated Successfully...',
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
    </script>
  <?php  
}

catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'Invalid form inputs',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back to view students!',
    }).then(function() {
        window.location = "view_students.php";
    })
</script> <?php
}

?>


