<?php 
session_start();
include("../db.php");


$query_courses = 'select class_section.crn, class_section.course_name, course.course_id, department.department_name,  class_section.section, user.first_name, user.last_name, building.building_name, room.room_number, ts_day.day_id, period.period_start, period.period_end, semester.semester_name, class_section.available_seats   from class_section
inner join department_faculty on class_section.faculty_id = department_faculty.faculty_id
inner join department on department_faculty.department_id = department.department_id
inner join faculty on department_faculty.faculty_id = faculty.faculty_id
inner join user on faculty.faculty_id = user.user_id
inner join building on class_section.building_id = building.building_id
inner join room on class_section.room_id = room.room_id
inner join course on class_section.course_name = course.course_name
inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
inner join ts_day on time_slot.day_id = ts_day.time_slot_day
inner join period on time_slot.period_id = period.period_id
inner join semester on class_section.semester_id = semester.semester_id
where class_section.semester_id = "SEMS2022"
order by course.course_id';
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
    <title>Master Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/master.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <style>
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #72778f !important;
            background-color: #72778f !important;
            margin-bottom: 1em;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: #333645;
            padding: 4px;
        }

        label {
            color: #fff;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #fff;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #fff !important;
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
            <div class="relative inline-block w-100 text-gray-700">
                <h2 class="text-white">Select Semester:</h2>
                <select id="s_sem" name="semester_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="'SEMS2022'">Spring 2022</option>
                    <option value="'SEMF2022'">Fall 2022</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="m-4"></div>
            <div class="relative inline-block w-100 text-gray-700">
                <h2 class="text-white">Select Department:</h2>
                <select id="s_dep" name="department_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
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
            <input id="lol" class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <script type="text/javascript">
            document.getElementById('s_sem').value = "<?php echo $_POST['semester_name'];?>";
        </script>
        <script type="text/javascript">
            document.getElementById('s_dep').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php 
            if(isset($_POST['department_name']) && $_POST['semester_name']){
                $sem_name = $_POST['semester_name'];
                $dep_name = $_POST['department_name'];
                if($dep_name == "'All Departments'"){
                    $query_courses = 'select class_section.crn, class_section.course_name, course.course_id, department.department_name,  class_section.section, user.first_name, user.last_name, building.building_name, room.room_number, ts_day.day_id, period.period_start, period.period_end, semester.semester_name, class_section.available_seats   from class_section
                    inner join department_faculty on class_section.faculty_id = department_faculty.faculty_id
                    inner join department on department_faculty.department_id = department.department_id
                    inner join faculty on department_faculty.faculty_id = faculty.faculty_id
                    inner join user on faculty.faculty_id = user.user_id
                    inner join building on class_section.building_id = building.building_id
                    inner join room on class_section.room_id = room.room_id
                    inner join course on class_section.course_name = course.course_name
                    inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
                    inner join ts_day on time_slot.day_id = ts_day.time_slot_day
                    inner join period on time_slot.period_id = period.period_id
                    inner join semester on class_section.semester_id = semester.semester_id
                    where class_section.semester_id = '." $sem_name ". '
                    order by course.course_id;';

                    $courses_statement = $db->prepare($query_courses);
                    $courses_statement->execute();
                    $courses = $courses_statement->fetchAll();
                    $courses_statement->closeCursor();
                }
                else{
                    $query_courses = 'select class_section.crn, class_section.course_name, course.course_id, department.department_name,  class_section.section, user.first_name, user.last_name, building.building_name, room.room_number, ts_day.day_id, period.period_start, period.period_end, semester.semester_name, class_section.available_seats   from class_section
                    inner join department_faculty on class_section.faculty_id = department_faculty.faculty_id
                    inner join department on department_faculty.department_id = department.department_id
                    inner join faculty on department_faculty.faculty_id = faculty.faculty_id
                    inner join user on faculty.faculty_id = user.user_id
                    inner join building on class_section.building_id = building.building_id
                    inner join room on class_section.room_id = room.room_id
                    inner join course on class_section.course_name = course.course_name
                    inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
                    inner join ts_day on time_slot.day_id = ts_day.time_slot_day
                    inner join period on time_slot.period_id = period.period_id
                    inner join semester on class_section.semester_id = semester.semester_id
                    where class_section.semester_id = '." $sem_name ". '
                    and department.department_name = '." $dep_name ".'
                    order by course.course_id;';
                    $courses_statement = $db->prepare($query_courses);
                    $courses_statement->execute();
                    $courses = $courses_statement->fetchAll();
                    $courses_statement->closeCursor();
                }
            }
        ?> <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"> <?php 
            if(!isset($_POST['semester_name'])){ 
               echo 'Master Schedule Spring 2022' ?> <?php } else { 
               switch($_POST['semester_name']){
                   case "'SEMF2022'":
                        echo 'Master Schedule Fall 2022';
                        break;
                   case "'SEMS2022'":
                        echo 'Master Schedule Spring 2022';
                        break;
                   case "'SEMF2021'":
                        echo 'Master Schedule Fall 2021';
                        break;
                   case "'SEMS2021'":
                        echo 'Master Schedule Spring 2021';
                        break;
                   case "'SEMF2020'":
                        echo 'Master Schedule Fall 2020';
                        break;
                   case "'SEMS2020'":
                        echo 'Master Schedule Spring 2020';
                        break;
                   case "'SEMF2019'":
                        echo 'Master Schedule Fall 2019';
                        break;
                   case "'SEMS2019'":
                        echo 'Master Schedule Spring 2019';
                        break;
                   case "'SEMF2018'":
                        echo 'Master Schedule Fall 2018';
                        break;
                   case "'SEMS2018'":
                        echo 'Master Schedule Spring 2018';
                        break;
                   case "'SEMF2017'":
                        echo 'Master Schedule Fall 2017';
                        break;
                   case "'SEMS2017'":
                        echo 'Master Schedule Spring 2017';
                        break;
               }
           } ?> </span>
        <div class="mx-8 my-4 flex flex-col">
            <table id="example">
                <thead>
                    <tr>
                        <th> CRN </th>
                        <th> Course Name </th>
                        <th> Course # </th>
                        <th> Department </th>
                        <th> Section </th>
                        <th> Professor </th>
                        <th> Building </th>
                        <th> Room # </th>
                        <th> DAY </th>
                        <th> Start Time </th>
                        <th> End Time </th>
                        <th> Semester </th>
                        <th> Avaliable Seats </th>
                    </tr>
                </thead>
                <tbody> <?php foreach ($courses as $course) : ?> <tr class="hover:bg-gray-50">
                        <td><?php echo $course['crn']; ?> </td>
                        <td><?php echo $course['course_name']; ?> </td>
                        <td><?php echo $course['course_id']; ?> </td>
                        <td><?php echo $course['department_name']; ?> </td>
                        <td><?php echo $course['section']; ?> </td>
                        <td><?php echo $course['first_name']." ".$course['last_name']; ?> </td>
                        <td><?php echo $course['building_name']; ?> </td>
                        <td><?php echo $course['room_number']; ?> </td>
                        <td><?php echo $course['day_id']; ?> </td>
                        <td><?php echo $course['period_start']; ?> </td>
                        <td><?php echo $course['period_end']; ?> </td>
                        <td><?php
                        $str = $course['semester_name'];
                        echo substr($str, 0, strlen($str) - 2). ' '. substr($str,strlen($str)-2);
                        ?> </td>
                        <td><?php
                        $rand = rand(0,29);
                        echo ($course['available_seats'] - $rand); ?> </td>
                    </tr><?php endforeach; ?> </tbody>
            </table>
        </div>
        </table>
        <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">Back To Top</a>
                </li>
            </ul>
        </footer>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>