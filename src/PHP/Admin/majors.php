<?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select * from major
inner join department on major.department_id = department.department_id;';
$courses_statement = $db->prepare($query_courses);
$courses_statement->execute();
$courses = $courses_statement->fetchAll();
$courses_statement->closeCursor();

 $majorErr =  "";
 $ba = "'%B.A.'";
 $bs = "'%B.S.'";
 $ms = "'%M.S.'";
 $phd = "'%Ph.D.'";




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/home.css">
</head>

<body> <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if(empty($_POST['majorType'])){
        $majorErr = "Major Type Required"; ?> <script type="text/javascript">
        let timerInterval
        Swal.fire({
            title: 'Please Select a Major Type',
            html: 'I will close in <b></b> milliseconds.',
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })
    </script> <?php }
}
    ?> <style>
        .error {
            color: #F8646C;
        }

        /* Compiled dark classes from Tailwind */
        .dark .dark\:divide-gray-700> :not([hidden])~ :not([hidden]) {
            border-color: rgba(55, 65, 81);
        }

        .dark .dark\:bg-gray-50 {
            background-color: rgba(249, 250, 251);
        }

        .dark .dark\:bg-gray-100 {
            background-color: rgba(243, 244, 246);
        }

        .dark .dark\:bg-gray-600 {
            background-color: rgba(75, 85, 99);
        }

        .dark .dark\:bg-gray-700 {
            background-color: rgba(55, 65, 81);
        }

        .dark .dark\:bg-gray-800 {
            background-color: rgba(31, 41, 55);
        }

        .dark .dark\:bg-gray-900 {
            background-color: rgba(17, 24, 39);
        }

        .dark .dark\:bg-red-700 {
            background-color: rgba(185, 28, 28);
        }

        .dark .dark\:bg-green-700 {
            background-color: rgba(4, 120, 87);
        }

        .dark .dark\:hover\:bg-gray-200:hover {
            background-color: rgba(229, 231, 235);
        }

        .dark .dark\:hover\:bg-gray-600:hover {
            background-color: rgba(75, 85, 99);
        }

        .dark .dark\:hover\:bg-gray-700:hover {
            background-color: rgba(55, 65, 81);
        }

        .dark .dark\:hover\:bg-gray-900:hover {
            background-color: rgba(17, 24, 39);
        }

        .dark .dark\:border-gray-100 {
            border-color: rgba(243, 244, 246);
        }

        .dark .dark\:border-gray-400 {
            border-color: rgba(156, 163, 175);
        }

        .dark .dark\:border-gray-500 {
            border-color: rgba(107, 114, 128);
        }

        .dark .dark\:border-gray-600 {
            border-color: rgba(75, 85, 99);
        }

        .dark .dark\:border-gray-700 {
            border-color: rgba(55, 65, 81);
        }

        .dark .dark\:border-gray-900 {
            border-color: rgba(17, 24, 39);
        }

        .dark .dark\:hover\:border-gray-800:hover {
            border-color: rgba(31, 41, 55);
        }

        .dark .dark\:text-white {
            color: rgba(255, 255, 255);
        }

        .dark .dark\:text-gray-50 {
            color: rgba(249, 250, 251);
        }

        .dark .dark\:text-gray-100 {
            color: rgba(243, 244, 246);
        }

        .dark .dark\:text-gray-200 {
            color: rgba(229, 231, 235);
        }

        .dark .dark\:text-gray-400 {
            color: rgba(156, 163, 175);
        }

        .dark .dark\:text-gray-500 {
            color: rgba(107, 114, 128);
        }

        .dark .dark\:text-gray-700 {
            color: rgba(55, 65, 81);
        }

        .dark .dark\:text-gray-800 {
            color: rgba(31, 41, 55);
        }

        .dark .dark\:text-red-100 {
            color: rgba(254, 226, 226);
        }

        .dark .dark\:text-green-100 {
            color: rgba(209, 250, 229);
        }

        .dark .dark\:text-blue-400 {
            color: rgba(96, 165, 250);
        }

        .dark .group:hover .dark\:group-hover\:text-gray-500 {
            color: rgba(107, 114, 128);
        }

        .dark .group:focus .dark\:group-focus\:text-gray-700 {
            color: rgba(55, 65, 81);
        }

        .dark .dark\:hover\:text-gray-100:hover {
            color: rgba(243, 244, 246);
        }

        .dark .dark\:hover\:text-blue-500:hover {
            color: rgba(59, 130, 246);
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
    <!-- Sidebar -->
    <?php include("./menu.php"); ?>
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
        <form class="m-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <!-- add a select box containing options -->
            <!-- for SELECT query -->
            <h2 class="text-white">Select Department:</h2>
            <div class="relative inline-block w-100 text-gray-700">
                <select id="select" name="department_name" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                    <option value="'All Departments'">All Departments</option>
                    <option value="'Accounting, Taxation & Business Law'">Accounting, Taxation & Business Law</option>
                    <option value="'American Studies/Media & Communications'">American Studies/Media & Communications</option>
                    <option value="'Biological Sciences'">Biological Sciences</option>
                    <option value="'English'">English</option>
                    <option value="'Exceptional Education & Learning'">Exceptional Education & Learning</option>
                    <option value="'History & Philosophy'">History & Philosophy</option>
                    <option value="'Mathematics, Computer & Information Science'">Mathematics, Computer & Information Science</option>
                    <option value="'Modern Languages'">Modern Languages</option>
                    <option value="'Politics, Economics & Law'">Politics, Economics & Law</option>
                    <option value="'Psychology'">Psychology</option>
                    <option value="'Public Health'">Public Health</option>
                    <option value="'Visual Arts'">Visual Arts</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-white-700">Major Type:</span>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" name="majorType" value="undergraduate" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'undergraduate') echo "checked";?>>
                        <span class="ml-2">Undergraduate</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio" name="majorType" value="graduate" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'graduate') echo "checked";?>>
                        <span class="ml-2">Graduate</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="majorType" <?php if (isset($_POST['majorType']) && $_POST['majorType'] == 'both') echo "checked";?> value="both"><span class="ml-2">Both</span>
                        <span class="error">* <?php echo $majorErr;?></span>
                    </label>
                </div>
            </div>
            <input class="block mt-5" type="submit" value="Submit"></p>
        </form>
        <script type="text/javascript">
            document.getElementById('select').value = "<?php echo $_POST['department_name'];?>";
        </script> <?php 
            if( $majorErr != "Major Type Required"){
                if(isset($_POST['department_name']) && $_POST['majorType']){
                    $dep_name = $_POST['department_name'];
                    $major_type = $_POST['majorType'];

                    if($dep_name == "'All Departments'" && $major_type == "both"){
                        $query_courses = 'select * from major
                        inner join department on major.department_id = department.department_id;';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name == "'All Departments'" && $major_type == "undergraduate"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where major.major_name LIKE '.$ba.' or major.major_name LIKE '.$bs.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name == "'All Departments'" && $major_type == "graduate"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where major.major_name LIKE '.$ms.' or major.major_name LIKE '.$phd.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "graduate"){
                        $query_courses = 'select major.major_name, department_name from major
inner join department on major.department_id = department.department_id
where (major.major_name like '.$ms.' or major.major_name like '.$phd.') and department.department_name = '.$dep_name.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "undergraduate"){
                        $query_courses = 'select major.major_name, department_name from major
inner join department on major.department_id = department.department_id
where (major.major_name like '.$ba.' or major.major_name like '.$bs.') and department.department_name = '.$dep_name.';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else if ($dep_name != "'All Departments'" && $major_type == "both"){
                        $query_courses = 'select * from major
inner join department on major.department_id = department.department_id
where department.department_name = '." $dep_name ".';';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                }
         } ?> <span class="ml-8 bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Majors</span>
        <div class="mx-8 flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table id="myTable" class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Major Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400"> Department Name </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['major_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $course['department_name']; ?> </td>
                                </tr><?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </table>
        <script type="text/javascript">
            let x = document.getElementById("myTable").rows.length;
            if (x == 1) {
                Swal.fire({
                    title: 'Warning!',
                    text: "<?php echo 'There is no '.$_POST['majorType'].' degree from the '.$_POST['department_name'].' department.'; ?>",
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    showCloseButton: true
                })
            }
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
    <script src="../JavaScript/hamburger_menu.js"></script>
</body>

</html>