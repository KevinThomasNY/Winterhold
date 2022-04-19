<!-- This page displays all 1500 students, you can view each student's id, name, student type, and major.
Also you can access their personal info like address, and view their transcripts/degree audit--> <?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select * from user
inner join student on student.student_id = user.user_id
inner join student_major on student.student_id = student_major.student_id
inner join major on major.major_id = student_major.major_id
where user_type = "Student";';
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
    <title>Admin Homepage</title>
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

        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Students</span>

        <div class="mx-8 my-4 flex flex-col">
            <table id="example">
                <thead>
                    <tr>
                        <th> Student ID </th>
                        <th> Name </th>
                        <th> Student Type </th>
                        <th> Major </th>
                        <th> Info </th>
                        <th> Transcript </th>
                        <th> Degree Audit</th>
                    </tr>
                </thead>
                <tbody> <?php foreach ($courses as $course) : ?> <tr class="hover:bg-gray-50">
                        <td><?php echo $course['student_id']; ?> </td>
                        <td><?php echo $course['first_name']." ".$course['last_name']; ?> </td>
                        <td><?php echo $course['student_type']; ?> </td>
                        <td><?php echo $course['major_name']; ?> </td>
                        <!-- Passing the data below using a hidden form, therefore, the values are not shown in the URL -->
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <form action="view_info.php" method="post">
                                <input type="hidden" name="first_name" value="<?php echo $course['first_name'] ?>" />
                                <input type="hidden" name="student_id" value="<?php echo $course['student_id'] ?>" />
                                <input type="hidden" name="student_type" value="<?php echo $course['student_type'] ?>" />
                                <input type="submit" name="whatever" value="View Info" id="hyperlink-style-button" />
                            </form>
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><a class="font-medium text-red-600 dark:text-red-500 hover:underline" href="view_transcript.php?varname=<?php  echo $course['student_id']?>&first_name=<?php echo $course['first_name']; ?>">View Transcript</a> </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><a class="font-medium text-green-600 dark:text-green-500 hover:underline" href="view_degree_audit.php?varname=<?php  echo $course['student_id']?>&first_name=<?php echo $course['first_name']; ?>">View Degree Audit</a> </td>
                    </tr><?php endforeach; ?> </tbody>
            </table>
        </div>
        </table>
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