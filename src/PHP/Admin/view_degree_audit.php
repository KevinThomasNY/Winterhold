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
$first = $_POST['first_name'];
$student_id = $_POST['student_id'];
$student_type = $_POST['student_type'];

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
$minor_query_info = $db->prepare("select * from student_minor
inner join minor on student_minor.minor_id = minor.minor_id where student_id = $student_id;");
$minor_query_info->execute();
$count_minor = $minor_query_info->rowCount();
$minor_id = null;
$minor_name = null;
if($count_minor > 0){
    while ($rows = $minor_query_info->fetch()){
        $minor_id = $rows['minor_id'];
        $minor_name = $rows['minor_name'];
    }
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
        
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo $first."'s Transcript"; ?></span>
        <div class="mx-8 mb-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> CRN </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Grade</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Credits</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Semester</th>
                                </tr>
                            </thead> <?php $pre ?> <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['crn']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_id']?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['grade']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_credits']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php
                                    $str = $course['semester_name'];
                                    echo substr($str, 0, strlen($str) - 2). ' '. substr($str,strlen($str)-2);
                                    ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo "Current Courses ". $first." is Taking"; ?></span>
        <div class="mx-8 mb-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> CRN </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course # </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Semester</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Grade So Far In Course (GPA Not Calculated until gradding deadline)</th>
 
                                </tr>
                            </thead>
                            <?php $pre ?>
                            <tbody> <?php foreach ($courses2 as $course2) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['crn']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['course_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['course_id']?>  </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php
                                    $str = $course2['semester_name'];
                                    echo substr($str, 0, strlen($str) - 2). ' '. substr($str,strlen($str)-2);
                                    ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course2['grade']?>  </td>
                                    
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
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