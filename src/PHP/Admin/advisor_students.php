<!-- View all the advisors and number of students their advising. The details button takes admin to a new page where the students information is listed -->
<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");

$user_id = $_POST['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$department_name = $_POST['department_name'];
$number_of_students = $_POST['number_of_students'];

$query_courses = 'select advisor.student_id, user.first_name, user.last_name, major.major_name, student.student_type, advisor.date_of_assignment from advisor
inner join user on user.user_id = advisor.student_id
inner join student_major on student_major.student_id = advisor.student_id
inner join major on major.major_id = student_major.major_id
inner join student on advisor.student_id = student.student_id
where faculty_id = '.$user_id.';';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();

$query_add_student = 'select undergraduate_student.student_id, user.first_name, user.last_name, major.major_name, student.student_type
from undergraduate_student 
inner join department on department.department_id = undergraduate_student.department_id
inner join user on user.user_id = undergraduate_student.student_id
inner join student_major on undergraduate_student.student_id = student_major.student_id
inner join major on student_major.major_id = major.major_id
inner join student on student.student_id = undergraduate_student.student_id
where not undergraduate_student.student_id in(select advisor.student_id from advisor
inner join user on user.user_id = advisor.student_id
inner join student_major on student_major.student_id = advisor.student_id
inner join major on major.major_id = student_major.major_id
inner join student on advisor.student_id = student.student_id
where faculty_id = '.$user_id.') and department.department_name = "'.$department_name.'"
UNION
select graduate_student.student_id, user.first_name, user.last_name, major.major_name, student.student_type
from graduate_student
inner join department on department.department_id = graduate_student.department_id
inner join user on user.user_id = graduate_student.student_id
inner join student_major on graduate_student.student_id = student_major.student_id
inner join major on student_major.major_id = major.major_id
inner join student on student.student_id = graduate_student.student_id
where not graduate_student.student_id in(select advisor.student_id from advisor
inner join user on user.user_id = advisor.student_id
inner join student_major on student_major.student_id = advisor.student_id
inner join major on major.major_id = student_major.major_id
inner join student on advisor.student_id = student.student_id
where faculty_id = '.$user_id.') and department.department_name = "'.$department_name.'";';
$add_student_statement = $db->prepare($query_add_student);
$add_student_statement->execute();
$add_students = $add_student_statement->fetchAll();
$add_student_statement->closeCursor();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor's Students</title>
    <link rel="shortcut icon" type="image/png" href="../../resources/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/master.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <style>
        /* Custom style */
        .btn_add_student {
            padding: 5px 15px;
            text-decoration: none;
            color: #fff;
            background-color: #1E3A8A;
            text-align: center;
            letter-spacing: .5px;
            transition: background-color .2s ease-out;
            cursor: pointer;
        }
        .btn_remove_student {
            padding: 5px 15px;
            text-decoration: none;
            color: #fff;
            background-color: #F8646C;
            text-align: center;
            letter-spacing: .5px;
            transition: background-color .2s ease-out;
            cursor: pointer;
        }
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
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo $first_name . " " . $last_name . " is Advising " . $number_of_students . " Students"; ?></span>
        <div class="mb-8 mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Student ID </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> First Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Last Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Major Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Student Type </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Date of Assignment </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Remove Student </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['student_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['first_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['last_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['major_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['student_type']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['date_of_assignment']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-red-500 whitespace-nowrap "><a href="./advisor_drop_student.php?id=<?php echo $course['student_id']; ?>&faculty_id=<?php echo $user_id; ?>" class="btn_remove_student">Remove <svg class="inline h-5 w-5 text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />  <line x1="8" y1="12" x2="16" y2="12" /></svg></a></td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <span class="ml-8  bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo "Select a Student from Department: ".$department_name." to add to " . $first_name . " " . $last_name . "'s Advising " .  "List"; ?></span>
        <div class="mx-8 my-4 flex flex-col">
            <table id="example">
                <thead>
                    <tr>
                        <th> Student ID </th>
                        <th> First Name </th>
                        <th> Last Name </th>
                        <th> Student Type </th>
                        <th> Major </th>
                        <th> Add Student </th>
                    </tr>
                </thead>
                <tbody> <?php foreach ($add_students as $add_student) : ?> <tr class="hover:bg-gray-50">
                        <td><?php echo $add_student['student_id']; ?></td>
                        <td><?php echo $add_student['first_name']; ?></td>
                        <td><?php echo $add_student['last_name']; ?></td>
                        <td><?php echo $add_student['student_type']; ?></td>
                        <td><?php echo $add_student['major_name']; ?></td>
                        <td>
                            <a href="./advisor_add_student.php?id=<?php echo $add_student['student_id']; ?>&faculty_id=<?php echo $user_id; ?>" class="btn_add_student">Add Student <svg class="inline h-5 w-5 text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />  <line x1="12" y1="8" x2="12" y2="16" />  <line x1="8" y1="12" x2="16" y2="12" /></svg></a>
                        </td>
                    </tr><?php endforeach; ?> </tbody>
            </table>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>