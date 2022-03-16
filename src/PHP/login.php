<?php 
session_start();
include("db.php");
?>
<?php
$msg = ""; 
if(isset($_POST['submitBtnLogin'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  if($username != "" && $password != "") {
    try {
      #$query = "select * from login where `email`=:username and `password`=:password";
      $query = "select *
      from users
      INNER JOIN login
      ON users.user_id = login.user_id
      where login.email =:username AND login.password =:password ";

      $stmt = $db->prepare($query);
      $stmt->bindParam('username', $username, PDO::PARAM_STR);
      $stmt->bindValue('password', $password, PDO::PARAM_STR);
      $stmt->execute();
      $count = $stmt->rowCount();
      $row   = $stmt->fetch(PDO::FETCH_ASSOC);
      #if user_id == password 
      if($count == 1 && !empty($row)) {
        $_SESSION['sess_user_id']   = $row['user_id'];
        $_SESSION['sess_first_name'] = $row['first_name'];
        $_SESSION['sess_last_name'] = $row['last_name'];
        $_SESSION['sess_user_type'] = $row['user_type'];

        if($row['user_type'] == "Student"){
            header("location: student.php");
        }
        if($row['user_type'] == "Faculty"){
            header("location: faculty.php");
        }
        if($row['user_type'] == "Admin"){
            header("location: admin.php");
        } 
        if($row['user_type'] == "Researcher"){
            header("location: researcher.php");
        }       
      }
      else {
        $msg = "Invalid email or password!";
      }
    } catch (PDOException $e) {
      echo "Error : ".$e->getMessage();
    }
  } else {
    $msg = "Both fields are required!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="../css/home.css">
</head>
<body>
<div class="container">
      <header class="header">
        <nav class="navbar">
          <a href="../home.html" class="nav-logo">Winterhold University</a>
          <ul class="nav-menu">
            <li class="nav-item">
              <a href="../home.html" class="nav-link register">Home</a>
            </li>
          </ul>
          <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
          </div>
        </nav>
      </header>
      <section class="login">
        <h1>Login</h1>
        <form method="post">
          <label for="fname">Email address</label><br />
          <input type="email" id="username" name="username" required  /><br />
          <br />
          <label for="lname">Password</label><br />
          <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="off"
          /><br /><br />
          <a href="#">Forgot your password?</a><br />
          <br />
            <input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Submit" />
            <span class="loginMsg"><?php echo @$msg;?></span>
        </form>
      </section>
    </div>
  <script src="../JavaScript/hamburger_menu.js"></script>
</body>
</html>