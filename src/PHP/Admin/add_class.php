<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select * from minor
inner join department on minor.department_id = department.department_id;';
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
    <title>Add Class</title>
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/home.css">
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

        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Add Class</span>
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            
            <?php
            // Get Course Name
            $query_course = 'select * from course';
            $course_statement = $db->prepare($query_course);
            $course_statement->execute();
            $courses = $course_statement->fetchAll();
            $course_statement->closeCursor();
            ?>
            <h2 class="text-white">Select Course:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select required id="select" name="course_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option disabled selected value> -- select a Course -- </option>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?=$course['course_name'];?>"><?=$course['course_name'] . " (Course# " . $course['course_id'].")";?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <?php
            // Get Course Name
            $query_faculty = 'select distinct user.user_id, user.first_name, user.last_name, department.department_name, user.date_of_birth, user.address, user.city, user.state, user.zip  from user
            inner join faculty on faculty.faculty_id = user.user_id
            inner join department_faculty on department_faculty.faculty_id = faculty.faculty_id
            inner join department on department.department_id = department_faculty.department_id
            inner join faculty_history on faculty.faculty_id = faculty_history.faculty_id
            where user_type = "Faculty";';
            $faculty_statement = $db->prepare($query_faculty);
            $faculty_statement->execute();
            $facultys = $faculty_statement->fetchAll();
            $faculty_statement->closeCursor();
            ?>
            <h2 class="mt-3 text-white">Select Professor:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select required id="select" name="faculty_id" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option disabled selected value> -- select a Professor -- </option>
                    <?php foreach ($facultys as $faculty) : ?>
                        <option value="<?=$faculty['user_id'];?>"><?=$faculty['first_name'] . " " . $faculty['last_name']. " (Department: " . $faculty['department_name'].")";?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
       <?php if(isset($_POST['course_name'])){
           $course_name =  $_POST['course_name'];
           $faculty_id = $_POST['faculty_id'];
           echo "Course selected: " . $course_name;
           echo "<br>";
           //The below query is used to get current class amount
            $result = $db->query('select count(crn) as count
            from class_section
            where semester_id = "SEMF2022" and faculty_id = "'.$faculty_id.'";');

            while ($rows = $result->fetch()){
            $current_classes = $rows['count'];
            }
            echo "This Professor is currently teaching " . $current_classes . " classes";
            echo "<br>";
            if($current_classes == 2 || $current_classes == 4){
                echo "This Professor's schedule is currently full. Select another professor.";
                echo "<br>";
            }else{
                echo "This Professor's schedule is not full. Below are the available times for this professor";
                echo "<br>";
                //The below query is used to get time slots
                $tsArray = array();
                $result2 = $db->query('select time_slot.time_slot_id from faculty_history
                inner join class_section on class_section.crn = faculty_history.crn
                inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
                where faculty_history.semester_id = "SEMS2022" and faculty_history.faculty_id = '.$faculty_id.';');
                //get student current timeslots
                while ($rows2 = $result2->fetch()){
                $tsArray[] = $rows2['time_slot_id'];
                }
                //Get timeslots not used by professor
                $imploded_arr = implode("','", $tsArray);
                $lol = "'" . implode("','", $tsArray) . "'";
                // Get Free Time slots
                // $query_ts = 'select * from time_slot
                // where time_slot.time_slot_id  NOT IN ('.$lol.')';
                $query_ts = 'select time_slot.time_slot_id, ts_day.day_id, period.period_start, period.period_end from time_slot
                inner join ts_day on time_slot.day_id = ts_day.time_slot_day
                inner join day on ts_day.day_id = day.day_id
                inner join period on period.period_id = time_slot.period_id
                where time_slot.time_slot_id  NOT IN ('.$lol.') order by ts_day.day_id;';
                $ts_statement = $db->prepare($query_ts);
                $ts_statement->execute();
                $tss = $ts_statement->fetchAll();
                $ts_statement->closeCursor();
                echo "<br>";
                } ?>
            <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="faculty_id" value="<?= $faculty_id; ?>" />
            <input type="hidden" name="course_name" value="<?= $course_name; ?>" />
            <h2 class="text-white">Available Time Slots for selected professor:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select required id="select" name="time_slot_id" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option disabled selected value> -- select  Day & Time -- </option>
                    <?php foreach ($tss as $ts) : ?>
                        <option value="<?=$ts['time_slot_id'];?>"><?=$ts['day_id'] . "  " . $ts['period_start']."-". $ts['period_end'];?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-white">Select Building & Room:</h2>
            <?php
                $query_build = 'select * from building
                inner join room on room.building_id = building.building_id
                where building.building_id = 1 or building.building_id = 2;';
                $build_statement = $db->prepare($query_build);
                $build_statement->execute();
                $builds = $build_statement->fetchAll();
                $build_statement->closeCursor();
                ?>
            <div class="relative inline-block w-100 text-gray-700">
                <select required id="select" name="room" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option disabled selected value> -- select  Buiding & Room -- </option>
                    <?php foreach ($builds as $build) : ?>
                        <option value="<?=$build['room_id'];?>"><?=$build['building_used'] . "  " . $build['room_number']?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <h2 class="mt-3 text-white">Enter Seats Available:</h2>
            <input type="number" name="seats" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-30 p-2.5" min="1" max="30" required>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
      <?php  } ?>

        <?php
            if(isset($_POST['time_slot_id'])){
                $faculty_id = $_POST['faculty_id'];
                $course_name = $_POST['course_name'];
                $time_slot_id =  $_POST['time_slot_id'];
                $room_id = $_POST['room'];
                $seats = $_POST['seats'];
                //Search for Building_id
                $result = $db->query('select building_id from room
                where room_id = "'.$room_id.'";');
                //Get building_id
                while ($rows = $result->fetch()){
                $building_id = $rows['building_id'];
                }
                //Check if building_id is available
                $ser = $db->prepare('select * from class_section 
                where semester_id = "SEMS2022" and time_slot_id = "'.$time_slot_id.'" and room_id = "'.$room_id.'"');
                $ser->execute();
                $count = $ser->rowCount();
                if($count == 0){
                    //room is avaialable
                $ser = $db->prepare('select * from class_section 
                where semester_id = "SEMS2022" and course_name = "'.$course_name.'"');
                $ser->execute();
                $section = $ser->rowCount();
                $section = $section + 1;
                //find largest crn
                $result = $db->query('select MAX(crn) as crn from class_section');
                //Get crn
                while ($rows = $result->fetch()){
                $crn = $rows['crn'];
                }
                $crn = $crn + 5;
                //Get course_id
                $result = $db->query('select course_id from course where course_name = "'.$course_name.'"');
                //Get crn
                while ($rows = $result->fetch()){
                $course_id = $rows['course_id'];
                }
                $query = "insert into class_section values ($crn, '$course_name', $section, $faculty_id, $building_id, '$room_id', 'SEMF2022', '$time_slot_id', $seats)";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $query = "insert into faculty_history values ($faculty_id, $crn, 'SEMF2022', '$course_id')";
                $stmt = $db->prepare($query);
                $stmt->execute();
                echo "Class Added";
                }
                
                else{
                    echo "Another class is teaching in the same room at the same time.";
                }
            }
        ?>
        <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="home_master_schedule.php">Go Back to View Master Schedule<svg class="inline h-5 w-5 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg> </a></button>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>