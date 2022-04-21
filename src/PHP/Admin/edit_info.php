<!-- View the student address and edit info --> <?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");
$student_id = $_POST['student_id'];
$email = $_POST['email'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$date_of_birth = $_POST['date_of_birth'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/form.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <style>
        /* Custom style */
        .header-right {
            width: calc(100% - 3.5rem);
        }

        .sidebar:hover {
            width: 16rem;
        }

        @media only screen and (min-width: 768px) {
            .header-right {
                width: calc(100% - 16rem);
            }
        }
    </style>
    <!-- Sidebar --> <?php include("./menu.php"); ?>
    <!-- ./Sidebar -->
    <div class="h-full ml-14 mt-14 mb-10 md:ml-64 ">
        <header class="header m-8">
            <nav class="navbar">
                <a href="../../home.html" class="nav-logo">Winterhold University</a>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../../home.html" class="nav-link register">Home</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </header>
        <span class="m-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo 'Edit ' . $first_name . "'s Info"; ?></span>
        <form action="update_info.php" method="post" class="m-8">
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" readonly  name="student_id" class="block py-2.5 px-0 w-full text-sm text-grey-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " required="" value="<?php echo $student_id ?>">
                <label for="floating_email" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Student ID</label>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="email" name="email" class="block py-2.5 px-0 w-full text-sm text-grey-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " required="" value="<?php echo $email ?>">
                <label for="floating_email" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="{7,15}" title="Password must be between 7-15 characters"  required="" value="<?php echo $password ?>">
                <label for="floating_password" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
            </div>


            <div class="grid xl:grid-cols-2 xl:gap-6">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="first_name" id="floating_first_name" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[a-zA-Z]{1,30}" title="First name must be max 30 characters and contain letters only" required="" value="<?php echo $first_name ?>">
                    <label for="floating_first_name" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First name</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="last_name" id="floating_last_name" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[a-zA-Z]{1,30}" title="Last name must be max 30 characters and contain letters only" required="" value="<?php echo $last_name ?>">
                    <label for="floating_last_name" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last name</label>
                </div>
            </div>
                        <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="date_of_birth" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[0-9/]{8,10}" title="MM/DD/YYYY" required="" value="<?php echo $date_of_birth ?>">
                <label for="floating_password" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Date of Birth (MM/DD/YYYY)</label>
            </div>
            <div class="grid xl:grid-cols-2 xl:gap-6">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="address" id="floating_phone" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[A-Za-z0-9 ,#'\/.]{3,50}" title="Address must can be max 50 characters" required="" value="<?php echo $address ?>">
                    <label for="floating_phone" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Address</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="city" id="floating_company" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[a-zA-Z\- ]{2,30}" title="Address must can be max 30 characters" required="" value="<?php echo $city ?>">
                    <label for="floating_company" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">City</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="state" id="floating_company" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[A-Z]{2}" title="Must be two characters only" required="" value="<?php echo $state ?>">
                    <label for="floating_company" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">State</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="zip" id="floating_company" class="block py-2.5 px-0 w-full text-sm text-white-900 bg-transparent border-0 border-b-2 border-white-300 appearance-none dark:text-white dark:border-white-600 dark:focus:border-red-500 focus:outline-none focus:ring-0 focus:border-red-600 peer" placeholder=" " pattern="[0-9]{5}" title="Must be 5 characters and numbers only allowed" required="" value="<?php echo $zip ?>">
                    <label for="floating_company" class="absolute text-sm text-green-400 dark:text-white-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-red-600 peer-focus:dark:text-red-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Zip</label>
                </div>

            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
                <footer class=" p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="./view_students.php">Go Back To View Student</a></button>
                </li>
            </ul>
        </footer>

    </div>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>