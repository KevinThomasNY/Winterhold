<!-- View all faculty members and view their class schedules. From the class schedules you can see how many students and their names in each class --> <?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select distinct user.user_id, user.first_name, user.last_name, department.department_name, user.date_of_birth, user.address, user.city, user.state, user.zip  from user
inner join faculty on faculty.faculty_id = user.user_id
inner join department_faculty on department_faculty.faculty_id = faculty.faculty_id
inner join department on department.department_id = department_faculty.department_id
inner join faculty_history on faculty.faculty_id = faculty_history.faculty_id
where user_type = "Faculty";';
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
    <title>View Faculty</title>
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
        .btn_remove_hold {
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #72778f;
            text-align: center;
            letter-spacing: .5px;
            transition: background-color .2s ease-out;
            cursor: pointer;
        }

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
                    <option value="'Biological Sciences'">Biological Sciences</option>
                    <option value="'English'">English</option>
                    <option value="'Exceptional Education & Learning'">Exceptional Education & Learning</option>
                    <option value="'History & Philosophy'">History & Philosophy</option>
                    <option value="'Mathematics, Computer & Information Science'">Mathematics, Computer & Information Science</option>
                    <option value="'Modern Languages'">Modern Languages</option>
                    <option value="'Politics, Economics & Law'">Politics, Economics & Law</option>
                    <option value="'Psychology'">Psychology</option>
                    <option value="'Public Health'">Public Health</option>
                    <option value="'Visual Arts'">Visual Arts</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <script type="text/javascript">
            document.getElementById('select').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php 
                if(isset($_POST['department_name'])){
                    $dep_name = $_POST['department_name'];

                    if($dep_name == "'All Departments'"){
                        $query_courses = 'select distinct user.user_id, user.first_name, user.last_name, department.department_name, user.date_of_birth, user.address, user.city, user.state, user.zip  from user
                        inner join faculty on faculty.faculty_id = user.user_id
                        inner join department_faculty on department_faculty.faculty_id = faculty.faculty_id
                        inner join department on department.department_id = department_faculty.department_id
                        inner join faculty_history on faculty.faculty_id = faculty_history.faculty_id
                        where user_type = "Faculty";';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else {
                            $query_courses = 'select distinct user.user_id, user.first_name, user.last_name, department.department_name, user.date_of_birth, user.address, user.city, user.state, user.zip from user
                            inner join faculty on faculty.faculty_id = user.user_id
                            inner join department_faculty on department_faculty.faculty_id = faculty.faculty_id
                            inner join faculty_history on faculty.faculty_id = faculty_history.faculty_id
                            inner join department on department.department_id = department_faculty.department_id
                            where user_type = "Faculty" and department.department_name = '.$dep_name.';';
                            $courses_statement = $db->prepare($query_courses);
                            $courses_statement->execute();
                            $courses = $courses_statement->fetchAll();
                            $courses_statement->closeCursor();
                    }
                }
          ?> <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Faculty</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Faculty ID</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> First Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Last Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Department </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> DOB </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Address </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> City </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> State </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Zip Code </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Schedule </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['user_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['first_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['last_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['department_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['date_of_birth']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['address']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['city']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['state']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['zip']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><a href="faculty_schedule.php?id=<?php echo $course['user_id']; ?>" class="btn_remove_hold">View Schedule <svg class="inline h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <circle cx="10" cy="10" r="7" />
                                                <line x1="7" y1="10" x2="13" y2="10" />
                                                <line x1="10" y1="7" x2="10" y2="13" />
                                                <line x1="21" y1="21" x2="15" y2="15" />
                                            </svg></a>
                                    </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </table>
        <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
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