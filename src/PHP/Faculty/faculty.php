<!-- Admin Home Page
    This page displays the Admins Name & email
-->
<?php
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
    #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
    ?> <?php
} else {
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $_SESSION['sess_first_name'] ?></title>
    <link
        rel="shortcut icon"
        type="image/png"
        href="../../resources/images/favicon.png"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/home.css" />
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
<!-- /Sidebar -->
<!-- Header -->
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
    <!-- /Header -->
    <!-- Welcome Card -->
    <div class=" mx-auto w-2/4 max-w-3xl bg-slate-500 rounded-lg">
        <img class="rounded-t-lg" src="../../resources/images/logininHome.png" alt="">
        <div class="p-5">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-white">Welcome <?php echo $_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name'];?></h5>
            <p class="mb-3 font-normal text-white-400">User Account:<?php echo " ". $_SESSION['sess_user_type'] ?></p>
            <p class="mb-3 font-normal text-white-400">Email:<?php echo " ". $_SESSION['sess_email'] ?></p>
            <a href="../logout.php">
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"> Log Out <svg class="h-5 w-5 text-white ml-2 -mr-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 12h14l-3 -3m0 6l3 -3" />
                    </svg>
                </button>
            </a>
        </div>
    </div>
    <!-- /Welcome Card -->
    <!-- Footer -->
    <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
        <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
            <li>
                <a href="#" class="mr-4 hover:underline md:mr-6 ">Back To Top</a>
            </li>
        </ul>
    </footer>
    <!-- /Footer -->
</div>
<script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>