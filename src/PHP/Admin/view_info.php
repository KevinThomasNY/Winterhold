<!-- View the student Information details and edit info by clicking the edit button, the edit button takes you to edit_info.php --> <?php
session_start();
if (isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "")
{
    #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
    
}
else
{
    header('location:login.php');
}
include ("../db.php");

$first = $_POST['first_name'];
$student_id = $_POST['student_id'];
$student_type = $_POST['student_type'];

$query_courses;
// Below is the query if the student is a undergraduate_student
if ($student_type == 'Undergraduate')
{
    $query_courses = 'select user.user_id, user.first_name, user.last_name, user.date_of_birth, user.city,
    user.address, user.state, user.zip, login.email, login.password, student.student_type, major.major_name,
    undergraduate_student.student_year, undergraduate_student.student_type as status
    from user
    inner join login on login.user_id = user.user_id
    inner join student on student.student_id = user.user_id
    inner join student_major on student.student_id = student_major.student_id
    inner join major on major.major_id = student_major.major_id
    inner join undergraduate_student on user.user_id = undergraduate_student.student_id
    where student.student_id = ' . $student_id . ';';
}
// Below is the query if the student id a graduate student
// I am using sql alias name becuse student_type is used in two different tables
else
{
    $query_courses = 'select  user.user_id, user.first_name, user.last_name, user.date_of_birth, user.city,
    user.address, user.state, user.zip, login.email, login.password, student.student_type, major.major_name,
    graduate_student.student_type as status
    from user
    inner join login on login.user_id = user.user_id
    inner join student on student.student_id = user.user_id
    inner join student_major on student.student_id = student_major.student_id
    inner join major on major.major_id = student_major.major_id
    inner join graduate_student on user.user_id = graduate_student.student_id
    where student.student_id = ' . $student_id . ';';
}
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
    <title>Student Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/form.css">
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
    <!-- Sidebar --> <?php include ("./menu.php"); ?>
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
        <span class="mx-8 bg-blue-100 text-blue-800 text-xl font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php echo $first . "'s Info"; ?></span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Student id </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Email </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Password </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Student Type </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Status </th>
                                    <?php
                                    if($student_type == 'Undergraduate')
                                    {
                                        echo '<th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Year </th>';
                                    }
                                    ?>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Major </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> DOB </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Address </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> City </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> State </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Zip </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Edit Info</th>
                                </tr>
                            </thead> <?php $pre ?> <tbody> <?php foreach ($courses as $course): ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['user_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['first_name'] . " " . $course['last_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['email']; ?></td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['password']; ?></td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['student_type']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['status']; ?> </td>
                                    <?php
                                    if($student_type == 'Undergraduate')
                                    {
                                    echo '<td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">' .$course['student_year']. '</td>';
                                    }
                                    ?>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['major_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['date_of_birth']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['address']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['city']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['state']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['zip']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <form action="edit_info.php" method="post">
                                            <input type="hidden" name="email" value="<?php echo $course['email'] ?>" />
                                            <input type="hidden" name="student_id" value="<?php echo $student_id ?>" />
                                            <input type="hidden" name="password" value="<?php echo $course['password'] ?>" />
                                            <input type="hidden" name="first_name" value="<?php echo $course['first_name'] ?>" />
                                            <input type="hidden" name="last_name" value="<?php echo $course['last_name'] ?>" />
                                            <input type="hidden" name="date_of_birth" value="<?php echo $course['date_of_birth'] ?>" />
                                            <input type="hidden" name="address" value="<?php echo $course['address'] ?>" />
                                            <input type="hidden" name="city" value="<?php echo $course['city'] ?>" />
                                            <input type="hidden" name="state" value="<?php echo $course['state'] ?>" />
                                            <input type="hidden" name="zip" value="<?php echo $course['zip'] ?>" />
                                            <input type="submit" name="whatever" value="Edit" id="hyperlink-style-button" />
                                        </form>
                                    </td>
                                </tr><?php
endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </table>
        <footer class=" p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022 <a href="../../home.html" class="hover:underline">Winterhold University</a>. All Rights Reserved. </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><a href="view_students.php">Go Back To View Students</a></button>
                </li>
            </ul>
        </footer>
    </div>
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>
