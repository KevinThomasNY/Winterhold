<!-- Degree Audit shows: GPA, course taken, current courses, and courses left from major/minor requirements -->
<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");
//get variables from form in view_student.php
$student_id = $_SESSION['sess_user_id'];

$query_courses = 'select * from student_history
inner join student_major on student_history.student_id = student_major.student_id
inner join major on major.major_id = student_major.major_id
inner join semester on semester.semester_id = student_history.semester_id
inner join course on course.course_id = student_history.course_id
where student_history.semester_id != "SEMS2022" and student_history.student_id = '.$student_id.';';

$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();

//The below query is used to in the student information section
$result = $db->query('select user.last_name, major.major_name, major.major_id, department.department_name, major.number_of_credits from user
inner join student on student.student_id = user.user_id
inner join student_major on  student_major.student_id = student.student_id
inner join major on major.major_id = student_major.major_id
inner join department on major.department_id = department.department_id
where user.user_id = '.$student_id.';');

while ($rows = $result->fetch()){
$last_name = $rows['last_name'];
$major_name = $rows['major_name'];
$major_id = $rows['major_id'];
$number_of_credits = $rows['number_of_credits'];
$department_name = $rows['department_name'];
}
//Below get only the letter grade to calculate gpa
//gpa is calulated by points divided by number of course taken
$gradesArray = array();
$result2 = $db->query('select grade from student_history
where student_history.semester_id != "SEMS2022" and student_id = '.$student_id.';');

while ($rows2 = $result2->fetch()){
$gradesArray[] = $rows2['grade'];
}

//sum of required courses credits Major
$result3 = $db->query('select SUM(course_credits) from major_requirements
inner join course on major_requirements.course_id = course.course_id
where major_id ='. $major_id.';');

while ($rows3 = $result3->fetch()){
$requiredCourseSum = $rows3['SUM(course_credits)'];
}

//sum of credits completed
$result4 = $db->query('select SUM(course_credits) from student_history
inner join student_major on student_history.student_id = student_major.student_id
inner join major on major.major_id = student_major.major_id
inner join semester on semester.semester_id = student_history.semester_id
inner join course on course.course_id = student_history.course_id
where student_history.semester_id != "SEMS2022" and student_history.student_id = '.$student_id.';');

while ($rows4 = $result4->fetch()){
$currentCourseSum = $rows4['SUM(course_credits)'];
}
//Below is the current courses_statement
$query_courses2 = 'select * from student_history
inner join student_major on student_history.student_id = student_major.student_id
inner join major on major.major_id = student_major.major_id
inner join semester on semester.semester_id = student_history.semester_id
inner join course on course.course_id = student_history.course_id
where student_history.semester_id = "SEMS2022" and student_history.student_id = '.$student_id.';';

$courses_statement2 = $db->prepare($query_courses2);
$courses_statement2->execute();
$courses2 = $courses_statement2->fetchAll();
$courses_statement2->closeCursor();
//Below is the major requirements query
$query_courses3 = 'select * from major_requirements
inner join course on major_requirements.course_id = course.course_id
where major_id ='. $major_id.';';

$courses_statement3 = $db->prepare($query_courses3);
$courses_statement3->execute();
$courses3 = $courses_statement3->fetchAll();
$courses_statement3->closeCursor();

//get all courses student taken
$completedCoursesArray = array();
$result5 = $db->query('select course_id from student_history
inner join semester on semester.semester_id = student_history.semester_id
where student_history.semester_id != "SEMS2022" and student_history.student_id = '.$student_id.' and (grade != "C-" and  grade != "D+" and  grade != "D" and  grade != "D-" and  grade != "F");');

while ($rows5 = $result5->fetch()){
$completedCoursesArray[] = $rows5['course_id'];
}

//get all in porgress courses student taking
$inprogressCoursesArray = array();
$result6 = $db->query('select course_id from student_history
inner join semester on semester.semester_id = student_history.semester_id
where student_history.semester_id = "SEMS2022" and student_history.student_id = '.$student_id.';');

while ($rows6 = $result6->fetch()){
$inprogressCoursesArray[] = $rows6['course_id'];
}
//Get Minor Information
$minor_id = null;
$result = $db->query('SELECT minor.minor_id, minor.minor_name
FROM student_minor inner join minor on student_minor.minor_id = minor.minor_id
WHERE EXISTS
(SELECT minor.minor_id, minor.minor_name FROM student_minor inner join minor on student_minor.minor_id = minor.minor_id WHERE student_id = '.$student_id.');');

while ($rows = $result->fetch()){
    $minor_id = $rows['minor_id'];
    $minor_name = $rows['minor_name'];
}
//Below is the minor requirements query
if($minor_id != null){
    $query_minor_requirements = 'select * from minor_requirements
    inner join course on minor_requirements.course_id = course.course_id
    where minor_id ='. $minor_id.';';

    $minors_statement = $db->prepare($query_minor_requirements);
    $minors_statement->execute();
    $minors = $minors_statement->fetchAll();
    $minors_statement->closeCursor();
    //sum of required courses credits Minor
    $minor_total_credits = $db->query('select SUM(course_credits) from minor_requirements
    inner join course on minor_requirements.course_id = course.course_id
    where minor_id ='. $minor_id.';');

    while ($rows = $minor_total_credits->fetch()){
    $requiredCourseSumMinor = $rows['SUM(course_credits)'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degree Audit</title>
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
                        <div class="text-sm font-light tracking-wide text-gray-400 uppercase"> Welcome <?php echo '<h1>'.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';?> </div>
                    </div>
                </li>
                <li>
                    <a href="admin.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate"> Student Homepage </span>
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
                    <a href="admin.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
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
                <li>
                    <a href="view_transcript.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">View Transcript</span>
                    </a>
                </li>
                <li>
                    <a href="view_degree.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">View Degree Audit</span>
                    </a>
                </li>
                <li>
                    <a href="view_holds.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">View Holds</span>
                    </a>
                </li>
                <li>
                    <a href="view_current_schedule.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">View Current Scedule</span>
                    </a>
                </li>
                <li>
                    <a href="../logout.php" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
                        <span class="inline-flex justify-center items-center ml-4">
<svg class="h-5 w-5 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />  <path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Log Out</span>
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
        <?php
            $gpa = 0.00;
            $points = 0;
            $gradesArrayLength = count($gradesArray);
            if(count($gradesArray) > 0){
                foreach($gradesArray as $grade){
                    switch($grade){
                        case "A":
                            $points += 4.00;
                            break;
                        case "A-":
                            $points += 3.70;
                            break;
                        case "B+":
                            $points += 3.30;
                            break;
                        case "B":
                            $points += 3.00;
                            break;
                        case "B-":
                            $points += 2.70;
                            break;
                        case "C+":
                            $points += 2.30;
                            break;
                        case "C":
                            $points += 2.00;
                            break;
                        case "C-":
                            $points += 1.70;
                            break;
                        case "D+":
                            $points += 1.30;
                            break;
                        case "D":
                            $points += 1.00;
                            break;
                        case "D-":
                            $points += 0.70;
                            break;
                        case "F":
                            $points += 0.00;
                            break;
                    }
                }
                $gpa = $points / $gradesArrayLength;
            }
        ?>
        <div class="m-8 relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="py-.5 bg-white hover:bg-gray-100">
                <h1 class="px-5 py-3 text-xl text-black">Student Information</h1>
                <p class="px-5 py-1 text-base text-gray-600">Student Name:  <?php echo $first . " " . $last_name; ?> </p>
                <p class="px-5 py-1 text-base text-gray-600">Student ID: <?php echo $student_id; ?></p>
                <p class="px-5 py-1 text-base text-gray-600">Major: <?php echo $major_name; ?></p>
                <p class="px-5 py-1 text-base text-gray-600">Deparmtent: <?php echo $department_name; ?></p>
                <p class="px-5 py-1 text-base text-green-600">GPA: <?php echo number_format((float)$gpa, 2, '.', ''); ?></p>
                <p class="px-5 py-1 text-base text-gray-600">Current Completed Credits: <?php echo $currentCourseSum; ?></p>
                <p class="px-5 py-1 text-base text-gray-600">Major Total Required Credits: <?php echo $requiredCourseSum; ?></p>
                <p class="px-5 py-1 text-base text-gray-600"><?php if($minor_id == null){echo "No Minor";}else{echo "Minor: ".$minor_name;} ?></p>
                <p class="px-5 py-1 text-base text-gray-600"><?php if($minor_id == null){echo "";}else{echo "Minor Total Credits: ".$requiredCourseSumMinor;} ?></p>
            </div>
        </div>
        
        <!-- Major Requirements table -->
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo "Courses Requirements for $major_name"; ?></span>
                <div class="mx-8 mb-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Credits </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Min Grade </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Completed </th>
 

                                </tr>
                            </thead>
                            <?php $pre ?>
                            <tbody> <?php foreach ($courses3 as $course3) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course3['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course3['course_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course3['course_credits']?>  </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course3['min_grade']?>  </td>
                                    <td class="py-4 px-6 text-sm font-medium text-blue-900 whitespace-nowrap dark:text-white"><?php if(in_array($course3['course_id'], $completedCoursesArray)){
                                        ?><svg class="h-8 w-8 text-green-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M5 12l5 5l10 -10" /></svg> <?php
                                    } else if(in_array($course3['course_id'], $inprogressCoursesArray)){
                                            echo "In-progress";
                                    }?>  </td>
                                    
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Minor Requirements table -->
        <?php if($minor_id != null){ ?>
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo "Courses Requirements for $minor_name"; ?></span>
                <div class="mx-8 mb-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Credits </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Min Grade </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Completed </th>
 

                                </tr>
                            </thead>
                            <?php $pre ?>
                            <tbody> <?php foreach ($minors as $minor) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['course_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['course_credits']?>  </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['min_grade']?>  </td>
                                    <td class="py-4 px-6 text-sm font-medium text-blue-900 whitespace-nowrap dark:text-white"><?php if(in_array($minor['course_id'], $completedCoursesArray)){
                                        ?><svg class="h-8 w-8 text-green-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M5 12l5 5l10 -10" /></svg> <?php
                                    } else if(in_array($minor['course_id'], $inprogressCoursesArray)){
                                            echo "In-progress";
                                    }?>  </td>
                                    
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <footer class=" p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="view_students.php">Go Back To View Students</a></button>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../JavaScript/hamburger_menu.js"></script>
</body>

</html>