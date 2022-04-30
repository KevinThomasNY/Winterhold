<!-- View all the advisors and number of students their advising. The details button takes admin to a new page where the students information is listed -->
<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select Distinct advisor.faculty_id, user.first_name, user.last_name, department.department_name, count(*) as number_of_students from advisor inner join department_faculty on
department_faculty.faculty_id = advisor.faculty_id
inner join department on department.department_id = department_faculty.department_id
inner join user on user.user_id = advisor.faculty_id
group by advisor.faculty_id
order by department_name;';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisors</title>
    <link rel="shortcut icon" type="image/png" href="../../resources/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/form.css">
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
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <!-- add a select box containing options -->
            <!-- for SELECT query -->
            <h2 class="text-white">Select Department:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select id="select" name="department_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="'All Departments'">All Departments</option>
                    <option value="'Accounting, Taxation & Business Law'">Accounting, Taxation & Business Law</option>
                    <option value="'American Studies/Media & Communications'">American Studies/Media & Communications</option>
                    <option value="'English'">English</option>
                    <option value="'Exceptional Education & Learning'">Exceptional Education & Learning</option>
                    <option value="'History & Philosophy'">History & Philosophy</option>
                    <option value="'Mathematics, Computer & Information Science'">Mathematics, Computer & Information Science</option>
                    <option value="'Modern Languages'">Modern Languages</option>
                    <option value="'Politics, Economics & Law'">Politics, Economics & Law</option>
                    <option value="'Psychology'">Psychology</option>
                    <option value="'Visual Arts'">Visual Arts</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <input class="block cursor-pointer rounded-lg mt-5 text-lg text-white bg-[#f8646c] px-9 py-2.5" type="submit" value="Submit"></p>
        </form>
        <script type="text/javascript">
            document.getElementById('select').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php
                if(isset($_POST['department_name']) ){
                    $dep_name = $_POST['department_name'];

                    if($dep_name == "'All Departments'"){
                        $query_courses = 'select Distinct advisor.faculty_id, user.first_name, user.last_name, department.department_name, count(*) as number_of_students from advisor inner join department_faculty on
                        department_faculty.faculty_id = advisor.faculty_id
                        inner join department on department.department_id = department_faculty.department_id
                        inner join user on user.user_id = advisor.faculty_id
                        group by advisor.faculty_id
                        order by department_name;';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else 
                    {
                        $query_courses = 'select Distinct advisor.faculty_id, user.first_name, user.last_name, department.department_name, count(*) as number_of_students from advisor inner join department_faculty on
                        department_faculty.faculty_id = advisor.faculty_id
                        inner join department on department.department_id = department_faculty.department_id
                        inner join user on user.user_id = advisor.faculty_id
                        where department.department_name = '.$dep_name.'
                        group by advisor.faculty_id
                        order by department_name;';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                }
            
                ?> <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Advisors</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Faculty ID </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> First Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Last Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Department Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Number of Students </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> View Students </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['faculty_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['first_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['last_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['department_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['number_of_students']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        <form action="advisor_students.php" method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $course['faculty_id'] ?>" />
                                            <input type="hidden" name="first_name" value="<?php echo $course['first_name'] ?>" />
                                            <input type="hidden" name="last_name" value="<?php echo $course['last_name'] ?>" />
                                            <input type="hidden" name="department_name" value="<?php echo $course['department_name'] ?>" />
                                            <input type="hidden" name="number_of_students" value="<?php echo $course['number_of_students'] ?>" />
                                            <input type="submit" name="whatever" value="View Students" id="hyperlink-style-button" />
                                        </form>
                                    </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
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