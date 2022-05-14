<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


//The below total amount of each user
$query_courses = 'select user_type, count(*) as total
from user
where user_type != "Researcher"
group by user_type;';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();
//For Pie Chart
$result = $db->query('select user_type, count(*) as total
from user
where user_type != "Researcher"
group by user_type;');

while ($rows = $result->fetch()){
  $user_type[] = $rows['user_type'];
  $total[] = $rows['total'];
}
//For student pie Chart
$result = $db->query('select student_type, count(*) as total_students
from student
group by student_type;');

while ($rows = $result->fetch()){
  $student_type[] = $rows['student_type'];
  $total_students[] = $rows['total_students'];
}
$query_students = 'select student_type, count(*) as total_students
from student
group by student_type;';
$student_statement = $db->prepare($query_students);
$student_statement->execute();
$students = $student_statement->fetchAll();
$student_statement->closeCursor();
//Major bar chart
$query_majors = 'select major.major_name, count(*) as total_major from major
inner join student_major on major.major_id = student_major.major_id
group by major.major_name';
$major_statement = $db->prepare($query_majors);
$major_statement->execute();
$majors = $major_statement->fetchAll();
$major_statement->closeCursor();

$result = $db->query('select major.major_name, count(*) as total_major from major
inner join student_major on major.major_id = student_major.major_id
group by major.major_name');

while ($rows = $result->fetch()){
  $major_name[] = $rows['major_name'];
  $total_majors[] = $rows['total_major'];
}
//Minor Bar Chart
$query_minors = 'select minor.minor_name, count(*) as total_minor from minor
inner join student_minor on minor.minor_id = student_minor.minor_id
group by minor.minor_name';
$minor_statement = $db->prepare($query_minors);
$minor_statement->execute();
$minors = $minor_statement->fetchAll();
$minor_statement->closeCursor();

$result = $db->query('select minor.minor_name, count(*) as total_minor from minor
inner join student_minor on minor.minor_id = student_minor.minor_id
group by minor.minor_name');

while ($rows = $result->fetch()){
  $minor_name[] = $rows['minor_name'];
  $total_minors[] = $rows['total_minor'];
}
//Faculty Bar Chart
$query_faculty = 'select department.department_name, count(*) as total_faculty from department
inner join department_faculty on department.department_id = department_faculty.department_id
group by department.department_name;';
$faculty_statement = $db->prepare($query_faculty);
$faculty_statement->execute();
$facultys = $faculty_statement->fetchAll();
$faculty_statement->closeCursor();

$result = $db->query('select department.department_name, count(*) as total_faculty from department
inner join department_faculty on department.department_id = department_faculty.department_id
group by department.department_name;');

while ($rows = $result->fetch()){
  $department_name[] = $rows['department_name'];
  $total_facultys[] = $rows['total_faculty'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="shortcut icon" type="image/png" href="../../resources/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <style>
        /* Custom style */
        body {
            background-color: #f2f2f2;
        }

        .nav-logo {
            color: #000000;
        }

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
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Total # of Each User Type</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> User Type </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Total Amount </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['user_type']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['total']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/4 m-auto">
            <canvas id="user_amount"></canvas>
        </div>
        <script>
            var pieChartUserType = {
                labels: <?php echo json_encode($user_type) ?> ,
                datasets: [{
                    label: 'My First Dataset',
                    data: <?php echo json_encode($total) ?> ,
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', ],
                    hoverOffset: 4
                }]
            };
            var config = {
                type: 'pie',
                data: pieChartUserType,
            };
            var myChart = new Chart(document.getElementById('user_amount'), config);
        </script>
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Total # of Each Student Type</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Student Type </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Total Amount </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($students as $student) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $student['student_type']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $student['total_students']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                <!-- Second Chart -->
        <div class="w-1/4 m-auto">
            <canvas id="student_amount"></canvas>
        </div>
        <script>
            var pieChartStudentType = {
                labels: <?php echo json_encode($student_type) ?> ,
                datasets: [{
                    label: 'My First Dataset',
                    data: <?php echo json_encode($total_students) ?> ,
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', ],
                    hoverOffset: 4
                }]
            };
            var config2 = {
                type: 'pie',
                data: pieChartStudentType,
            };
            var myChart2 = new Chart(document.getElementById('student_amount'), config2);          
        </script>
          <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Major Information</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Major Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Total Students In Each Major </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($majors as $major) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $major['major_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $major['total_major']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
          <div class="w-1/2 m-auto">
            <canvas id="majors"></canvas>
        </div>
        <script>
              var labels = <?php echo json_encode($major_name) ?>;
              var data = {
                labels: labels,
                datasets: [{
                  label: 'TOTAL STUDENTS IN EACH MAJOR',
                  data: <?php echo json_encode($total_majors) ?>,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                  ],
                  borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                  ],
                  borderWidth: 1
                }]
              };
            var config3 = {
              type: 'bar',
              data: data,
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              },
            };
            var myChart3 = new Chart(document.getElementById('majors'), config3);          
        </script>
          <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Minor Information</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Minor Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Total Students In Each Minor </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($minors as $minor) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['minor_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $minor['total_minor']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
          <div class="w-1/2 m-auto">
            <canvas id="minors"></canvas>
        </div>
        <script>
              var labels = <?php echo json_encode($minor_name) ?>;
              var data = {
                labels: labels,
                datasets: [{
                  label: 'TOTAL STUDENTS IN EACH MINOR',
                  data: <?php echo json_encode($total_minors) ?>,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                  ],
                  borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                  ],
                  borderWidth: 1
                }]
              };
            var config4 = {
              type: 'bar',
              data: data,
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              },
            };
        var myChart4 = new Chart(document.getElementById('minors'), config4);          
        </script>
          <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Faculty Information</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Department Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Total Faculty In Each Department </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($facultys as $faculty) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $faculty['department_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $faculty['total_faculty']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
          <div class="w-1/2 m-auto">
            <canvas id="faculty"></canvas>
        </div>
        <script>
              var labels = <?php echo json_encode($department_name) ?>;
              var data = {
                labels: labels,
                datasets: [{
                  label: 'TOTAL FACULTY IN EACH DEPARTMENT',
                  data: <?php echo json_encode($total_facultys) ?>,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                  ],
                  borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                  ],
                  borderWidth: 1
                }]
              };
            var config5 = {
              type: 'bar',
              data: data,
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              },
            };
        var myChart5 = new Chart(document.getElementById('faculty'), config5);          
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
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>