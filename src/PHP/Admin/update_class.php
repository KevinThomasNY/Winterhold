<!-- View the student address and edit info --> <?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");
$crn = $_POST['crn'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$day_id = $_POST['day_id'];
$period_start = $_POST['period_start'];
$period_end = $_POST['period_end'];

//The below query is used to get all details from class section
$result = $db->query('select * from class_section where crn = '.$crn.';');

while ($rows = $result->fetch()){
    $course_name = $rows['course_name'];
    $faculty_id = $rows['faculty_id'];
    $time_slot_id = $rows['time_slot_id'];
    $available_seats = $rows['available_seats'];
    $room_id = $rows['room_id'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Info</title>
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
        <span class="m-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Update Class for <?php echo $course_name; ?></span> <?php
            //Get all faculty
            $query_faculty = 'select distinct user.user_id, user.first_name, user.last_name, department.department_name, user.date_of_birth, user.address, user.city, user.state, user.zip  from user
            inner join faculty on faculty.faculty_id = user.user_id
            inner join department_faculty on department_faculty.faculty_id = faculty.faculty_id
            inner join department on department.department_id = department_faculty.department_id
            inner join faculty_history on faculty.faculty_id = faculty_history.faculty_id
            where faculty.faculty_id != '.$faculty_id.' and user_type = "Faculty";';
            $faculty_statement = $db->prepare($query_faculty);
            $faculty_statement->execute();
            $facultys = $faculty_statement->fetchAll();
            $faculty_statement->closeCursor();
            ?> <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <!-- Passing Hidden Inputs -->
            <input type="hidden" name="crn" value="<?= $crn; ?>" />
            <input type="hidden" name="first_name" value="<?= $first_name; ?>" />
            <input type="hidden" name="last_name" value="<?= $last_name; ?>" />
            <input type="hidden" name="day_id" value="<?= $day_id; ?>" />
            <input type="hidden" name="period_start" value="<?= $period_start; ?>" />
            <input type="hidden" name="period_end" value="<?= $period_end; ?>" />
            <!-- Faculty drop down -->
            <h2 class="mx-8 mt-5 text-white">Change Professor:</h2>
            <div class="mx-8 relative inline-block w-100 text-gray-700">
                <select required id="select" name="faculty_id" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="<?php echo $faculty_id ?>"> <?php echo $first_name . " " . $last_name . " (Current Professor)"; ?> </option> <?php foreach ($facultys as $faculty) : ?> <option value="<?=$faculty['user_id'];?>"><?=$faculty['first_name'] . " " . $faculty['last_name']. " (Department: " . $faculty['department_name'].")";?></option> <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <!-- Select time_slot --> <?php 
            $query_ts = 'select * from time_slot
            inner join ts_day on time_slot.day_id = ts_day.time_slot_day
            inner join day on ts_day.day_id = day.day_id
            inner join period on period.period_id = time_slot.period_id
            where time_slot_id != "'.$time_slot_id.'" order by ts_day.day_id ;';
            $ts_statement = $db->prepare($query_ts);
            $ts_statement->execute();
            $tss = $ts_statement->fetchAll();
            $ts_statement->closeCursor();
            ?> <h2 class="mx-8 mt-5 text-white">Change Time/Day:</h2>
            <div class="mx-8 relative inline-block w-100 text-gray-700">
                <select required id="select" name="time_slot_id" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="<?php echo $time_slot_id ?>"> <?php echo $day_id." ".$period_start."-".$period_end . " (Current Time/Day)"; ?> </option> <?php foreach ($tss as $ts) : ?> <option value="<?=$ts['time_slot_id'];?>"><?=$ts['day_id'] . "  " . $ts['period_start']."-". $ts['period_end'];?></option> <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <!-- Select room/building -->
            <h2 class="mx-8 mt-5 text-white">Change Building/Room:</h2> <?php
                    $query_build = 'select * from building
                    inner join room on room.building_id = building.building_id
                    where building.building_id = 1 or building.building_id = 2;';
                    $build_statement = $db->prepare($query_build);
                    $build_statement->execute();
                    $builds = $build_statement->fetchAll();
                    $build_statement->closeCursor();
                    ?> <div class="mx-8 relative inline-block w-100 text-gray-700">
                <select required id="select" name="room" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="<?php echo $room_id ?>"><?php echo $room_id ." (Current Building/Room)" ?> </option> <?php foreach ($builds as $build) : ?> <option value="<?=$build['room_id'];?>"><?=$build['building_used'] . "  " . $build['room_number']?></option> <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <!-- Select seat amount -->
            <h2 class="mx-8 mt-5 text-white">Change Seats Available:</h2>
            <input type="number" value="<?php echo $available_seats ?>" name="seats" class=" mx-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-30 p-2.5" min="1" max="30" required>
            <!-- Submit Button -->
            <input class=" mx-8 block mt-5" type="submit" value="Update"></p>
        </form> <?php
        // Execute the code in if statement when admin submits the form
        if(isset($_POST['faculty_id'])){
            //Save the form data VALUES
            $f_faculty_id = $_POST['faculty_id'];
            $f_time_slot_id = $_POST['time_slot_id'];
            $f_room = $_POST['room'];
            $f_seats = $_POST['seats'];
            //Check if Professor, time, & room are all the same.
            //Therefore, don't have to update faculty_history
            if($faculty_id == $f_faculty_id && $time_slot_id == $f_time_slot_id && $room_id == $f_room){
                $query = "update class_section set available_seats = ".$f_seats." where crn = ".$crn."";
                if($f_seats != $available_seats){
                    ?> <h2 class="mx-8 mt-5 text-white">Available Seats Updated</h2> <?php
                }
                $stmt = $db->prepare($query);
                $stmt->execute();
            }
            else{
                //Check if professor has room to teach.
                //The below query is used to get current class amount
                    $result = $db->query('select count(crn) as count
                    from class_section
                    where semester_id = "SEMF2022" and faculty_id = "'.$f_faculty_id.'";');

                    while ($rows = $result->fetch()){
                    $current_classes = $rows['count'];
                    }
                    ?> <h2 class="mx-8 mt-5 text-white">This Professor is currently teaching <?php echo $current_classes; ?> classes</h2> <?php    
                    // echo "This Professor is currently teaching " . $current_classes . " classes";
                    // echo "<br>";
                    if($current_classes == 2 || $current_classes == 4){
                    ?> <h2 class="mx-8 mt-5 text-white">This Professor's schedule is currently full. <span class="text-red-500"> Select another professor.</span></h2> <?php  
                        // echo "This Professor's schedule is currently full. Select another professor.";
                        // echo "<br>";
                    } 
                    else{               
                        //Check for a time conflict
                        //Get Professor's current time slots
                        $tsArray = array();
                        $result2 = $db->query('select time_slot_id from class_section where faculty_id = '.$f_faculty_id.' and semester_id = "SEMF2022"');
                        //the professors timeslots are stored in an array
                        while ($rows2 = $result2->fetch()){
                        $tsArray[] = $rows2['time_slot_id'];
                        } 
                        //check if form value time_slot is in the array.
                        if(in_array("$f_time_slot_id",$tsArray)){
                            ?> <h2 class="mx-8 mt-5 text-white">Time Conflict, Professor is teaching another class at the same time and day.</h2> <?php                    
                        }else{
                            //There is not a time conflict, procede
                            ?> <h2 class="mx-8 mt-5 text-white">The Professor is free to teach at this time and day.</h2> <?php
                            //Check if there is a room & time conflict
                            $ser = $db->prepare('select * from class_section 
                            where semester_id = "SEMS2022" and time_slot_id = "'.$f_time_slot_id.'" and room_id = "'.$f_room.'"');
                            $ser->execute();
                            $count = $ser->rowCount();
                            if($count == 0){
                                //room-time is avaialable
                                //Delete the old faculty from faculty_history
                                $query = 'DELETE from faculty_history where crn = '.$crn.'';
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                //Insert new faculty_id into faculty_history
                                //Get course_id
                                $result = $db->query('select course_id from course where course_name = "'.$course_name.'"');
                                while ($rows = $result->fetch()){
                                $course_id = $rows['course_id'];
                                }
                                //Search for Building_id
                                $result = $db->query('select building_id from room
                                where room_id = "'.$f_room.'";');
                                //Get building_id
                                while ($rows = $result->fetch()){
                                $building_id = $rows['building_id'];
                                }                                                                   
                                $query = "INSERT into faculty_history values ($f_faculty_id, $crn, 'SEMF2022', '$course_id')";
                                $stmt = $db->prepare($query);
                                $stmt->execute();                        
                                //Update the Master Schedule
                                $query = "UPDATE class_section set time_slot_id = '$f_time_slot_id', building_id = $building_id, room_id = '$f_room', faculty_id = $f_faculty_id, available_seats = $f_seats where crn = $crn";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                ?> <h2 class="mx-8 mt-5 text-white">No room/time conflict, <span class="text-green-500">Class Updated!</span></h2> <?php                  
                            }
                            else{
                                ?> <h2 class="mx-8 mt-5 text-white">Another class is teaching in the same room at the same time. <span class="text-red-500"> Class was not updated!</span></h2> <?php
                            }
                        }
                }               
            }
        }
        ?> <footer class=" p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="home_master_schedule.php">Go Back to View Master Schedule<svg class="inline h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                            </svg> </a></button>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>