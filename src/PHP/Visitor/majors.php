<?php 
session_start();
include("../db.php");


$query_courses = 'select * from major
inner join department on major.department_id = department.department_id;';
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
    <title>Majors</title>
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/home.css">
</head>

<body> <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if(empty($_POST['majorType'])){
        $majorErr = "Major Type Required"; ?> <script type="text/javascript">
        let timerInterval
        Swal.fire({
            title: 'Please Select a Major Type',
            html: 'I will close in <b></b> milliseconds.',
            timer: 1500,
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
        })
    </script> <?php }
}
    ?> <style>
        .error {
            color: #F8646C;
        }

        /* Compiled dark classes from Tailwind */
        .dark .dark\:divide-gray-700> :not([hidden])~ :not([hidden]) {
            border-color: rgba(55, 65, 81);
        }

        .dark .dark\:bg-gray-50 {
            background-color: rgba(249, 250, 251);
        }

        .dark .dark\:bg-gray-100 {
            background-color: rgba(243, 244, 246);
        }

        .dark .dark\:bg-gray-600 {
            background-color: rgba(75, 85, 99);
        }

        .dark .dark\:bg-gray-700 {
            background-color: rgba(55, 65, 81);
        }

        .dark .dark\:bg-gray-800 {
            background-color: rgba(31, 41, 55);
        }

        .dark .dark\:bg-gray-900 {
            background-color: rgba(17, 24, 39);
        }

        .dark .dark\:bg-red-700 {
            background-color: rgba(185, 28, 28);
        }

        .dark .dark\:bg-green-700 {
            background-color: rgba(4, 120, 87);
        }

        .dark .dark\:hover\:bg-gray-200:hover {
            background-color: rgba(229, 231, 235);
        }

        .dark .dark\:hover\:bg-gray-600:hover {
            background-color: rgba(75, 85, 99);
        }

        .dark .dark\:hover\:bg-gray-700:hover {
            background-color: rgba(55, 65, 81);
        }

        .dark .dark\:hover\:bg-gray-900:hover {
            background-color: rgba(17, 24, 39);
        }

        .dark .dark\:border-gray-100 {
            border-color: rgba(243, 244, 246);
        }

        .dark .dark\:border-gray-400 {
            border-color: rgba(156, 163, 175);
        }

        .dark .dark\:border-gray-500 {
            border-color: rgba(107, 114, 128);
        }

        .dark .dark\:border-gray-600 {
            border-color: rgba(75, 85, 99);
        }

        .dark .dark\:border-gray-700 {
            border-color: rgba(55, 65, 81);
        }

        .dark .dark\:border-gray-900 {
            border-color: rgba(17, 24, 39);
        }

        .dark .dark\:hover\:border-gray-800:hover {
            border-color: rgba(31, 41, 55);
        }

        .dark .dark\:text-white {
            color: rgba(255, 255, 255);
        }

        .dark .dark\:text-gray-50 {
            color: rgba(249, 250, 251);
        }

        .dark .dark\:text-gray-100 {
            color: rgba(243, 244, 246);
        }

        .dark .dark\:text-gray-200 {
            color: rgba(229, 231, 235);
        }

        .dark .dark\:text-gray-400 {
            color: rgba(156, 163, 175);
        }

        .dark .dark\:text-gray-500 {
            color: rgba(107, 114, 128);
        }

        .dark .dark\:text-gray-700 {
            color: rgba(55, 65, 81);
        }

        .dark .dark\:text-gray-800 {
            color: rgba(31, 41, 55);
        }

        .dark .dark\:text-red-100 {
            color: rgba(254, 226, 226);
        }

        .dark .dark\:text-green-100 {
            color: rgba(209, 250, 229);
        }

        .dark .dark\:text-blue-400 {
            color: rgba(96, 165, 250);
        }

        .dark .group:hover .dark\:group-hover\:text-gray-500 {
            color: rgba(107, 114, 128);
        }

        .dark .group:focus .dark\:group-focus\:text-gray-700 {
            color: rgba(55, 65, 81);
        }

        .dark .dark\:hover\:text-gray-100:hover {
            color: rgba(243, 244, 246);
        }

        .dark .dark\:hover\:text-blue-500:hover {
            color: rgba(59, 130, 246);
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
    <!-- Sidebar -->
    <div class="fixed flex flex-col top-0 left-0 w-14 hover:w-64 md:w-64 bg-blue-900 dark:bg-gray-900 h-full text-white transition-all duration-300 border-none z-10 sidebar">
        <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">
            <ul class="flex flex-col py-4 space-y-1">
                <li class="px-5 hidden md:block">
                    <div class="flex flex-row items-center h-8">
                        <div class="text-sm font-light tracking-wide text-gray-400 uppercase"> Welcome </div>
                    </div>
                </li>
                <li>
                    <a href="../../home.html" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate"> Home </span>
                    </a>
                </li>
                <li>
                    <a href="majors.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Majors</span>
                    </a>
                </li>
                <li>
                    <a href="minors.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                                <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Minors</span>
                    </a>
                </li>
                <li>
                    <a href="departments.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
                                <line x1="12" y1="10" x2="12" y2="16" />
                                <line x1="9" y1="13" x2="15" y2="13" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Departments</span>
                    </a>
                </li>
                <li>
                    <a href="home_course_catolog.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Course Catolog</span>
                    </a>
                </li>
                <li>
                    <a href="home_master_schedule.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <polyline points="9 11 12 14 20 6" />
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Master Schedule</span>
                    </a>
                </li>
                <li>
                    <a href="home_calendar.html" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Academic Calendar</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- ./Sidebar -->
    <div class="h-full ml-14 mt-14 mb-10 md:ml-64 ">
        <header class="header m-8">
            <nav class="navbar">
                <a href="../../home.html" class="nav-logo">Winterhold University</a>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../../PHP/login.php" class="nav-link register">Log In</a>
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
            <div class="mt-4">
                <span class="text-white-700">Major Type:</span>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" name="majorType" value="undergraduate" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'undergraduate') echo "checked";?>>
                        <span class="ml-2">Undergraduate</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio" name="majorType" value="graduate" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'graduate') echo "checked";?>>
                        <span class="ml-2">Graduate</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="majorType" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'both') echo "checked";?> value="both"><span class="ml-2">Both</span>
                        <span class="error">* <?php echo $majorErr;?></span>
                    </label>
                </div>
            </div>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <script type="text/javascript">
            document.getElementById('select').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php 
            if( $majorErr != "Major Type Required"){
                if(isset($_POST['department_name']) && $_POST['majorType']){
                    $dep_name = $_POST['department_name'];
                    $major_type = $_POST['majorType'];

                    if($dep_name == "'All Departments'" && $major_type == "both"){
                        $query_courses = 'select * from major
                        inner join department on major.department_id = department.department_id;';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name == "'All Departments'" && $major_type == "undergraduate"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where major.major_name LIKE '.$ba.' or major.major_name LIKE '.$bs.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name == "'All Departments'" && $major_type == "graduate"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where major.major_name LIKE '.$ms.' or major.major_name LIKE '.$phd.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "graduate"){
                        $query_courses = 'select major.major_name, department_name from major
inner join department on major.department_id = department.department_id
where (major.major_name like '.$ms.' or major.major_name like '.$phd.') and department.department_name = '.$dep_name.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "undergraduate"){
                        $query_courses = 'select major.major_name, department_name from major
inner join department on major.department_id = department.department_id
where (major.major_name like '.$ba.' or major.major_name like '.$bs.') and department.department_name = '.$dep_name.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "both"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where department.department_name = '." $dep_name ".';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                }
         } ?> <span class="ml-8 bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Majors</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Major Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Department Name </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['major_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['department_name']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </table>
        <script type="text/javascript">
            let x = document.getElementById("myTable").rows.length;
            if (x == 1) {
                Swal.fire({
                    title: 'Warning!',
                    text: "<?php echo 'There is no '.$_POST['majorType'].' degree from the '.$_POST['department_name'].' department.'; ?>",
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    showCloseButton: true
                })
            }
        </script>
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