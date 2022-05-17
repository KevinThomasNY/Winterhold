<?php 
session_start();
include("db.php");
?>
<?php
$msg = ""; 
if(isset($_POST['submitBtnLogin'])) {
  $username = trim($_POST['username']);
  if($username != "") {
    try {
      $query = "select *
      from user
      INNER JOIN login
      ON user.user_id = login.user_id
      where login.email =:username";

      $stmt = $db->prepare($query);
      $stmt->bindParam('username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $count = $stmt->rowCount();
      $row   = $stmt->fetch(PDO::FETCH_ASSOC);
      if($count == 1 && !empty($row)) { 

        $email = $row['email'];
        $query = "insert into forgot_pw value ('$email');";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $letUser = "The Admin is notified that your password needs reseting. The admin will reset your password in 1-3 business days.";
        echo $letUser;
        }
      else {
        $msg = "Invalid email!";
      }
    } catch (PDOException $e) {
      echo "Error : ".$e->getMessage();
    }
  } else {
    $msg = "Email Empty";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot Password</title>
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
        <h1>Forgot Password</h1>
        <form method="post">
          <label for="fname">Email address</label><br />
          <input type="email" id="username" name="username" required  /><br />
          <br />
            <input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Submit" />
            <span class="loginMsg"><?php echo @$msg;?></span>
        </form>
      </section>
    </div>
  <script src="../JavaScript/hamburger_menu.js"></script>
</body>
</html>
