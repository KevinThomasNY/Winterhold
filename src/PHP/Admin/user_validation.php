<!-- Inserting user to user database table -->
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
# Get data to input
$user_type = $_POST['user_type'];
$first_name = filter_input(INPUT_POST, "first_name");
$last_name = filter_input(INPUT_POST, "last_name");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$address = filter_input(INPUT_POST, "address");
$city = filter_input(INPUT_POST, "city");
$state = filter_input(INPUT_POST, "state");
$zip = filter_input(INPUT_POST, "zip");
$date_of_birth = filter_input(INPUT_POST, "date_of_birth");
$student_id = rand(500000,599999);



    try{
    #Insert new user table in database
    $query = "INSERT INTO user VALUES ($student_id, '$first_name', '$last_name', '$date_of_birth', '$address', '$city', '$state', '$zip', '$user_type');";
    $stmt = $db->prepare($query);
    $stmt->execute();
    #Insert new user's Login info in login table database
    $query2 = "INSERT INTO login VALUES ($student_id, '$email', '$password', '$user_type');";
    $stmt = $db->prepare($query2);
    $stmt->execute();
    #Below is the Javascript success alert message
?> <script type="text/javascript">
    let timerInterval
    Swal.fire({
        title: 'User Added Successfully...',
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
        window.location = "view_all_users.php";
    })
    </script>
  <?php  
}

catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'Email already taken, try again',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back to add user!',
    }).then(function() {
        window.location = "add_user.php";
    })
</script> <?php
}

?>


