<!-- Drop user page 1. Purpose is to confrim if admin wants to delete a user. If admins preses cancel, the admin is taken back to view_all_users.php. If admin selects delete admin is then taken to drop_user_confrim.php where the user is deleted. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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

$user_id = trim($_GET['id']); ?>

 <script type="text/javascript">
Swal.fire({
  title: 'Are you sure you want to delete this user?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire( 
      'Deleted!',
      'User has been deleted.',
      'success'
    ).then(function() {
        window.location = "drop_user_confrim.php?id=<?php echo $user_id; ?>";
    })
  }
  else{
          Swal.fire(
      'No Changes!',
      'User was not deleted.',
      'error'
    ).then(function() {
        window.location = "view_all_users.php";
    })
  }
})
</script> 


	
