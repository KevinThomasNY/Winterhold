<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");

$id = $_SESSION['sess_user_id'];
$query_courses = "select class_section.crn, class_section.course_name, course.course_id, department.department_name,  class_section.section, user.first_name, user.last_name, building.building_name, room.room_number, ts_day.day_id, period.period_start, period.period_end, semester.semester_name, class_section.available_seats   from class_section
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
where class_section.semester_id = 'SEMS2022' and user.user_id = $id order by course.course_id";
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
    <title>Student Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/master.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
        <style>
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
                    where class_section.semester_id = '." $sem_name ". ' and user.user_id = '. $id .' order by course.course_id;';

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
                    where class_section.semester_id = '." $sem_name ". ' and user.user_id = '. $id .' 
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