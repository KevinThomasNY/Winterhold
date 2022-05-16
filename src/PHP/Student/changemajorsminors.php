<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");

$student_id = $_SESSION['sess_user_id'];

$query_courses = 'select * from major inner join department on major.department_id = department.department_id;';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();

 $majorErr =  "";
 $ba = "'%B.A.'";
 $bs = "'%B.S.'";
 $ms = "'%M.S.'";
 $phd = "'%Ph.D.'";


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Majors / Minors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/home.css">
</head>

<body> <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $major_id = trim($_POST['major_id']);
        $minor_id = trim($_POST['minor_id']);
        $student_id = $_SESSION['sess_user_id'];

        if (($major_id != 0) && ($minor_id == 0))
        {
            $date = date("m/d/Y");
            $query = "update `student_major` set major_id = $major_id where student_id = $student_id";
            $stmt = $db->prepare($query);
            $stmt->execute();
            header('location:./view_degree.php');
            die();
        }
        else if (($major_id == 0) && ($minor_id != 0)){
            $date = date("m/d/Y");
            #check if minor already exits.
            $result2 = $db->prepare("select * from student_minor where student_id = $student_id;");
            $result2->execute();
            $count = $result2->rowCount();
            if($count > 0){
                $query = "update student_minor set minor_id = $minor_id where student_id = $student_id";
                $stmt = $db->prepare($query);
                $stmt->execute();
                echo $student_id;
                header('location:./view_degree.php');
                die();
            }else{
                $query = "insert into student_minor (minor_id, student_id, date_added) values ($minor_id, $student_id, '$date')";
                $stmt = $db->prepare($query);
                $stmt->execute();
                header('location:./view_degree.php');
                die();
            }
        }
        else if (($major_id != 0) && ($minor_id != 0)){
            $date = date("m/d/Y");
            $query = "update `student_major` set major_id = $major_id where student_id = $student_id";
            $stmt = $db->prepare($query);
            $stmt->execute();

            #check if minor already exits.
            $result2 = $db->prepare("select * from student_minor where student_id = $student_id;");
            $result2->execute();
            $count = $result2->rowCount();
            if($count > 0){
                $query = "update `student_minor` set minor_id = $minor_id where student_id = $student_id";
                $stmt = $db->prepare($query);
                $stmt->execute();
                header('location:./view_degree.php');
                die();
            }else{
                $query = "insert into student_minor (minor_id, student_id, date_added) values ($minor_id, $student_id, '$date')";
                $stmt = $db->prepare($query);
                $stmt->execute();
                header('location:./view_degree.php');
                die();
            }
        }

        header('location:./view_degree.php');
        die();
    }
?> <style>
        .error {
            color: #F8646C;
        }

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
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark: text-blue-800">Change Major OR Change Minor</span>
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="student_id" value="<?= $student_id; ?>" />
            <!-- add a select box containing options -->
            <!-- for SELECT query --> <?php
            // major
            $query_major = 'select * from major where number_of_credits = 100';
            $major_statement = $db->prepare($query_major);
            $major_statement->execute();
            $majors = $major_statement->fetchAll();
            $major_statement->closeCursor();

            // minors
            $query_minors = 'select * from minor;';
            $minors_statement = $db->prepare($query_minors);
            $minors_statement->execute();
            $minors = $minors_statement->fetchAll();
            $minors_statement->closeCursor();
            ?> <div class="relative inline-block w-100 text-gray-700">
                <p class="text-white">Select The Major dropdown</p>
                <select id="select_01" name="major_id" class=" w-full my-3 h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="0"> - Select Major - </option> <?php foreach ($majors as $major) : ?> <option value="<?=$major['major_id'];?>"><?=$major['major_name'];?></option> <?php endforeach; ?>
                </select>
                <p class="text-white">Select The Minor Dropdown</p>
                <select id="select_02" name="minor_id" class=" w-full my-3 h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="0"> - Select Minor - </option> <?php foreach ($minors as $minor) : ?> <option value="<?=$minor['minor_id'];?>"><?=$minor['minor_name'];?></option> <?php endforeach; ?>
                </select>
            </div>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">Back To Top</a>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../JavaScript/hamburger_menu.js"></script>
</body>

</html>