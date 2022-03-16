<?php 
session_start();
include("db.php");

$query_courses = 'SELECT * FROM course';
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
    <title>Course Catolog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/home.css" />
</head>

<body>
    <div class="container">
        <header class="header">
            <nav class="navbar">
                <a href="../home.html" class="nav-logo">Winterhold University</a>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../home.html" class="nav-link register">Home</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </header>
        
        <span class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Courses</span>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course ID </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Course Name </th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php foreach ($courses as $course) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                     
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['course_name']; ?> </td>

                                </tr><?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
    <script src="../JavaScript/hamburger_menu.js"></script>
</body>

</html>