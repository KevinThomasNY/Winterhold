<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
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
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
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
    <?php include("./menu.php"); ?>
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
            <div class="relative inline-block w-100 text-gray-700">
                <h2 class="text-white">Select Semester:</h2>
                <select id="s_sem" name="semester_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="'SEMS2022'">Spring 2022</option>
                    <option value="'SEMF2022'">Fall 2022</option>
                    <option value="'SEMF2021'">Fall 2021</option>
                    <option value="'SEMS2021'">Spring 2021</option>
                    <option value="'SEMF2020'">Fall 2020</option>
                    <option value="'SEMS2020'">Spring 2020</option>
                    <option value="'SEMF2019'">Fall 2019</option>
                    <option value="'SEMS2019'">Spring 2019</option>
                    <option value="'SEMF2018'">Fall 2018</option>
                    <option value="'SEMS2018'">Spring 2018</option>
                    <option value="'SEMF2017'">Fall 2017</option>
                    <option value="'SEMS2017'">Spring 2017</option>
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
                        $result = $db->query('SELECT count(crn)
                        FROM student_history
                        WHERE crn = '.$course['crn'].';');

                        while ($rows = $result->fetch()){
                            $numCrn = $rows['count(crn)'];
                        }
                            if( $course['semester_name'] == "Fall22"){
                                echo $course['available_seats'];
                            }else if($course['semester_name'] == "Spring22"){
                                $avaSeats =    $course['available_seats'] - $numCrn;
                                if($avaSeats < 0 ){
                                    echo "0";
                                }else{
                                    echo $avaSeats;
                                }
                            }
                            else echo "No Seats Available";
                         ?> </td>
                    </tr><?php endforeach; ?> </tbody>
            </table>
        </div>
         <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
             <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
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