<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select course.course_id, course.course_credits, course.course_name, department.department_name
from course
inner join department on course.department_id = department.department_id
order by department.department_name;';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();
$preCourseName = "";
$errorMsg = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <link rel="shortcut icon" type="image/png" href="../../resources/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/home.css">
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
    <!-- Sidebar --> <?php  include("./menu.php"); ?>
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
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Add New Course</span>
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <!-- add a select box containing options -->
            <!-- for SELECT query -->
            <h2 class="text-white">Select Department:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select id="select" name="department_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="Accounting, Taxation & Business Law">Accounting, Taxation & Business Law</option>
                    <option value="American Studies/Media & Communications">American Studies/Media & Communications</option>
                    <option value="Biological Sciences">Biological Sciences</option>
                    <option value="English">English</option>
                    <option value="Exceptional Education & Learning">Exceptional Education & Learning</option>
                    <option value="History & Philosophy">History & Philosophy</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Computer & Information Science">Computer & Information Science</option>
                    <option value="Modern Languages">Modern Languages</option>
                    <option value="Politics, Economics & Law">Politics, Economics & Law</option>
                    <option value="Psychology">Psychology</option>
                    <option value="Public Health">Public Health</option>
                    <option value="Visual Arts">Visual Arts</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <h2 class="mt-3 text-white">Enter Course Name:</h2>
            <input type="text" name="courseName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5" pattern="[A-Za-z0-9 ,#'\/.]{3,100}" title="Course Name must be between 3-100 characters" required>
            <h2 class="mt-3 text-white">Enter Course Number (Must be exactly 4 digits):</h2>
            <input type="text" name="courseNumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5" pattern="[0-9]{4}" title="Course Number must be exactly 4 digits" required>
            <h2 class="mt-3 text-white">Enter Course Credits:</h2>
            <input type="number" name="courseCredits" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-30 p-2.5" min="1" max="20" required>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <!-- When the admin selects submit button the value form the optin input is saved-->
        <script type="text/javascript">
            document.getElementById('select').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php 
            if(isset($_POST['department_name'])){
                $dep_name = $_POST['department_name'];
                #Getting the abbreviation for each course
                switch($dep_name){
                    case "Accounting, Taxation & Business Law":
                        $preCourseName = "BU";
                        break;
                    case "American Studies/Media & Communications":
                        $preCourseName = "AS";
                        break;
                    case "Biological Sciences":
                        $preCourseName = "BS";
                        break;
                    case "English":
                        $preCourseName = "EL";
                        break;
                    case "Exceptional Education & Learning":
                        $preCourseName = "ED";
                        break;
                    case "History & Philosophy":
                        $preCourseName = "HI";
                        break;
                    case "Mathematics":
                        $preCourseName = "MA";
                        break;
                    case "Computer & Information Science":
                        $preCourseName = "CS";
                        break;
                    case "Modern Languages":
                        $preCourseName = "ML";
                        break;
                    case "Politics, Economics & Law":
                        $preCourseName = "PE";
                        break;
                    case "Psychology":
                        $preCourseName = "PY";
                        break;
                    case "Public Health":
                        $preCourseName = "PH";
                        break;
                    case "Visual Arts":
                        $preCourseName = "VA";
                        break;
                }
                #Saving the variables from admins input
                $course_name = $_POST['courseName'];
                $course_number = $_POST['courseNumber'];
                $course_credits = $_POST['courseCredits'];
                #Check if Course name and Course number already exists
                $result = $db->query('select course_name from course;');
                while ($rows = $result->fetch()){
                $course_name_array[] = $rows['course_name'];
                }
                if(in_array($course_name,$course_name_array)){
                    $errorMsg .= "Course name already used";
                }
                $result = $db->query('select course_id from course;');
                while ($rows = $result->fetch()){
                $course_number_array[] = $rows['course_id'];
                }
                $course_number = $preCourseName . $course_number;
                if(in_array($course_number,$course_number_array)){
                    $errorMsg .= "<br>Course number already used";
                }
                #Print error message if there is an error only
                if($errorMsg != ""){
                                            ?> <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                html: '<?php echo $errorMsg; ?>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Go back!',
            }).then(function() {
                window.location = "new_Course.php";
            })
        </script> <?php
                }

                #If there is no error add course to 'course' database
                if($errorMsg == ""){
                    try{
                            #This function surrounds a string with %.
                            function str_wrap($string = '', $char = '"')
                            {
                                return str_pad($string, strlen($string) + 2, $char, STR_PAD_BOTH);
                            }
                            $department_name = str_wrap($dep_name,'%');
                            #department_name = %$dep_name%
                            #Get department id
                            $result = $db->query('select department_id from department where department_name LIKE "'.$department_name.'";');

                            while ($rows = $result->fetch()){
                                $department_id = $rows['department_id'];
                            }
                            #Execute query
                            $query = "insert into course (course_id,course_name,course_credits,department_id) value ('$course_number','$course_name',$course_credits,$department_id)";
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            ?> <script type="text/javascript">
            let timerInterval
            Swal.fire({
                title: 'New Course Added Successfully...',
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
                window.location = "home_course_catolog.php";
            })
        </script> <?php
                        }

                    
                        catch(PDOException $e) {
                        ?> <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Course Not Added',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Go back!',
            }).then(function() {
                window.location = "new_Course.php";
            })
        </script> <?php
                        }
                        
                }


                
            }
        ?> <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">Back To Top</a>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>