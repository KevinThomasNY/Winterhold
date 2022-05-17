<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");
//get variables from form in view_student.php
$user_id = $_SESSION['sess_user_id'];

$query_courses = 'select faculty_history.crn, course.course_name, course.course_id, course.course_credits, class_section.section, ts_day.day_id, period.period_start, period.period_end from faculty_history
inner join class_section on faculty_history.crn = class_section.crn
inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
inner join ts_day on time_slot.day_id = ts_day.time_slot_day
inner join period on time_slot.period_id = period.period_id
inner join course on class_section.course_name = course.course_name
where faculty_history.faculty_id = '.$user_id.' and faculty_history.semester_id = "SEMS2022";';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();
//Fall 2022
$query_courses2 = 'select faculty_history.crn, course.course_name, course.course_id, course.course_credits, class_section.section, ts_day.day_id, period.period_start, period.period_end from faculty_history
inner join class_section on faculty_history.crn = class_section.crn
inner join time_slot on class_section.time_slot_id = time_slot.time_slot_id
inner join ts_day on time_slot.day_id = ts_day.time_slot_day
inner join period on time_slot.period_id = period.period_id
inner join course on class_section.course_name = course.course_name
where faculty_history.faculty_id = '.$user_id.' and faculty_history.semester_id = "SEMF2022";';
$courses_statement2 = $db->prepare($query_courses2);
$courses_statement2->execute();
$courses2 = $courses_statement2->fetchAll();
$courses_statement2->closeCursor();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Schedule</title>
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/home.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <style>
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
        

        
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Schedule For Spring 2022</span>
        <div class="mx-8 my-7 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> CRN </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Course Credits</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Section</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Class Info</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">View Students</th>
                                </tr>
                            </thead>  <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['crn']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_id']?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_credits']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['section']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap hover:underline dark:text-white"><a href="class_info.php?crn=<?php echo $course['crn'];?>" >Info</a>
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><a href="view_faculty_students.php?id=<?php echo $course['crn']; ?>&course_name=<?php echo $course['course_name']; ?>" class="btn_remove_hold">View Students <svg class="inline h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        <span class="mx-8  bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Schedule For Fall 2022</span>
        <div class="mx-8 m-4 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> CRN </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Course Credits</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Section</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">Class Info</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">View Students</th>
                                </tr>
                            </thead>  <tbody> <?php foreach ($courses2 as $course2) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['crn']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['course_id']?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['course_credits']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['section']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap hover:underline dark:text-white"><a href="class_info.php?crn=<?php echo $course['crn'];?>" >Info</a>
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><a href="view_faculty_students.php?id=<?php echo $course2['crn']; ?>&course_name=<?php echo $course2['course_name']; ?>" class="btn_remove_hold">View Students <svg class="inline h-5 w-5 text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        <?php
        //The below query is used to in the student information section
        $result = $db->query('select f_rank from faculty where faculty_id = '.$user_id.'');

        while ($rows = $result->fetch()){
        $f_rank = $rows['f_rank'];
        }
        ?>
        <script type="text/javascript">
            let x = document.getElementById("myTable").rows.length;
            if (x == 1) {
                Swal.fire({
                    title: 'Warning!',
                    text: "<?php echo "You are the Deparmtent " . $f_rank . ", therefore, you don't have any classes." ?>",
                    icon: 'info',
                    type: "warning",
                    confirmButtonText: 'Ok',
                }).then(function() {
                    window.location = "faculty.php";
                });
            }
        </script>
        <footer class=" p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
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