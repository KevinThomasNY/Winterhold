<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select time_slot.time_slot_id, ts_day.day_id, period.period_start, period.period_end from time_slot
inner join ts_day on time_slot.day_id = ts_day.time_slot_day
inner join day on ts_day.day_id = day.day_id
inner join period on period.period_id = time_slot.period_id
order by ts_day.day_id;';
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
    <title>Time Slot</title>
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
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Add Time Slot</span>
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h2 class="mt-3 text-white">Select Days:</h2>
            <input type="checkbox" name="day[]" value="M">
            <label> Monday</label><br>
            <input type="checkbox" name="day[]" value="T">
            <label> Tuesday</label><br>
            <input type="checkbox" name="day[]" value="W">
            <label> Wednesday</label><br>
            <input type="checkbox" name="day[]" value="R">
            <label> Thursday</label><br>
            <input type="checkbox" name="day[]" value="F">
            <label> Friday</label><br>
            <h2 class="mt-3 text-white">Start Time:</h2>
            <input style="color: #000000;" type="time" name="start" required>
            <h2 class="mt-3 text-white">End Time:</h2>
            <input style="color: #000000;" type="time" name="end" required>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form> <?php
            $selected_days = array();
            if(isset($_POST["day"])){
                foreach($_POST["day"] as $value) {
                 $selected_days[] = $value;
                }
            //The join funtion returns a string from an array.
            $selected_days = join($selected_days);
            //Get Start Time
            $start_time = $_POST["start"];
            $start_time = date("g:ia", strtotime("$start_time"));
            //Get End Time
            $end_time = $_POST["end"];
            $end_time = date("g:ia", strtotime("$end_time"));
            //Check if start and end times already exists
            $select = $db->prepare('SELECT period_start, period_end from period where period_start = ? and period_end = ?');
            $select->execute([$start_time,$end_time]);
            if ($select->rowCount() > 0) {
                //StartTime and EndTime Exist Already
                echo "The Selected Start Time And End Time Exist Already <br>";
            } else {
                //StartTime and EndTime do not exist; insert new time into database
                //Get row count from period database
                $result = $db->query('select count(*) from period;');
                while ($rows = $result->fetch()){
                $row_count = $rows['count(*)'];
                }
                //increment by 1
                $row_count++;
                $period_id = "TSP{$row_count}";
                try{
                    $query = "insert into period (period_id,period_start,period_end) values ('$period_id','$start_time','$end_time');";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    //Insert into TS_Day
                    //get each diferent day_id
                    $unique_day_id = array();
                    $result = $db->query('select distinct day_id from ts_day;');
                    while ($rows = $result->fetch()){
                        $unique_day_id[] = $rows['day_id'];
                    }
                    foreach ($unique_day_id as $day_value){
                        $time_slot_day = "TSD{$day_value}{$row_count}";
                        $query = "insert into ts_day (time_slot_day,day_id) values ('$time_slot_day','$day_value');";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        //Insert into time_slot
                        $time_slot_id = "TS{$day_value}P{$row_count}";
                        $period_id = "TSP{$row_count}";
                        $query2 = "insert into time_slot (time_slot_id, day_id, period_id) values ('$time_slot_id', '$time_slot_day', '$period_id');";
                        $stmt = $db->prepare($query2);
                        $stmt->execute();
                    }
                    echo "New start & end time added <br>";
                }
                catch(PDOException $e) {
                ?> <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Something went wrong',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Take me back!',
            }).then(function() {
                window.location = "time_slot.php";
            })
        </script> <?php
                }
            }
            //Check if Selected days already exists
            $select = $db->prepare('SELECT day_id from day where day_id = ?;');
            $select->execute([$selected_days]);
            if ($select->rowCount() > 0) {
                //Day Exist Already
                echo "The Selected day(s) already exists<br>";
            } else {
                    //Selected day does not exist; insert new time into database
                    //Get row count from period database
                    try{
                    $query = "insert into day (day_id) values ('$selected_days');";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    //Insert into TS_Day
                    //Insert based on period rows for the new selected day
                    
                    //Get row count from period database
                    $result = $db->query('select count(*) from period;');
                    while ($rows = $result->fetch()){
                        $row_count = $rows['count(*)'];
                    }

                    for($i = 1; $i <= $row_count; $i++){
                        $time_slot_day = "TSD{$selected_days}{$i}";
                        $query = "insert into ts_day (time_slot_day,day_id) values ('$time_slot_day','$selected_days');";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                    }
                    for($i = 1; $i <= $row_count; $i++){
                         //Insert into time_slot
                        $time_slot_day = "TSD{$selected_days}{$i}";
                        $time_slot_id = "TS{$selected_days}P{$i}";
                        $period_id = "TSP{$i}";
                        $query2 = "insert into time_slot (time_slot_id, day_id, period_id) values ('$time_slot_id', '$time_slot_day', '$period_id');";
                        $stmt = $db->prepare($query2);
                        $stmt->execute();
                    }
                    echo "New day(s)  added <br>";

                }
                catch(PDOException $e) {
                ?> <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Something went wrong',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Take me back!',
            }).then(function() {
                window.location = "time_slot.php";
            })
        </script> <?php
                }
            }
        
        }
    
        ?> <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Time Slot's</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Time Slot ID </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Days</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Period Start Time</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Period End Time</th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['time_slot_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['day_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['period_start']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['period_end']; ?> </td>
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