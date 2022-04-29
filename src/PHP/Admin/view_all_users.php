<!-- In this page you can view all the users in the database. You can filter between the user types at the top using the dropdown menu. You can edit user info or delete a user permanently in each row --> <?php 
session_start();
if(isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
  #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
} else { 
  header('location:login.php');
}
include("../db.php");


$query_courses = 'select user.user_id, user.first_name, user.last_name, user.date_of_birth, user.address, user.city, user.state, user.zip, user.user_type, login.email, login.password from user inner join login on login.user_id = user.user_id where user.user_type = "Student";';
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
    <title>View All Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../css/form.css" />
</head>

<body>
    <style>
        .btn_delete {
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #F8646C;
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
        <span class="ml-8 bg-blue-100 text-blue-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800"><?php 
            if(!isset($_POST['user_type'])){ 
               echo 'Viewing All Students' ?> <?php } else { 
                switch($_POST['user_type']){
                   case "'Student'":
                        echo 'Viewing All Students';
                        break;
                   case "'Faculty'":
                        echo 'Viewing All Faculty';
                        break;
                   case "'Admin'":
                        echo 'Viewing All Administrators';
                        break;
                   case "'Researcher'":
                        echo 'Viewing All Researchers';
                        break;
                   case "'All Users'":
                        echo 'Viewing All Users';
                        break;
               }
           } ?></span>
        <div class="mx-8 flex flex-col">
            <form class="my-4" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <!-- add a select box containing options -->
                <!-- for SELECT query -->
                <h2 class="text-white">Select User Type:</h2>
                <div class="relative inline-block w-100 text-gray-700">
                    <select id="select" name="user_type" class=" w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline">
                        <option value="'Student'">Students</option>
                        <option value="'Faculty'">Faculty</option>
                        <option value="'Admin'">Admin</option>
                        <option value="'Researcher'">Researcher</option>
                        <option value="'All Users'">All Users</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <input class="block cursor-pointer rounded-lg mt-5 text-lg text-white bg-[#f8646c] px-9 py-2.5" type="submit" value="Submit"></p>
            </form>
            <script type="text/javascript">
                document.getElementById('select').value = "<?php echo $_POST['user_type'];?>";
            </script> <?php 
                if(isset($_POST['user_type'])){
                    $user_type = $_POST['user_type'];

                    if($user_type == "'All Users'"){
                        $query_courses = 'select user.user_id, user.first_name, user.last_name, user.date_of_birth, user.address, user.city, user.state, user.zip, user.user_type, login.email, login.password from user inner join login on login.user_id = user.user_id';
                        $courses_statement = $db->prepare($query_courses);
                        $courses_statement->execute();
                        $courses = $courses_statement->fetchAll();
                        $courses_statement->closeCursor();
                    }
                    else {
                    $query_courses = 'select user.user_id, user.first_name, user.last_name, user.date_of_birth, user.address, user.city, user.state, user.zip, user.user_type, login.email, login.password from user inner join login on login.user_id = user.user_id where user.user_type = '. $user_type. ';';
                    $courses_statement = $db->prepare($query_courses);
                    $courses_statement->execute();
                    $courses = $courses_statement->fetchAll();
                    $courses_statement->closeCursor();
                    }
                }
          ?> <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">User ID</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">User Type</th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> First Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Last Name </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Email </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Password </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> DOB </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Address </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> City </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> State </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Zip Code </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Edit Info </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"> Delete User </th>
                                </tr>
                            </thead>
                            <tbody> <?php foreach ($courses as $course) : ?> <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['user_id']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['user_type']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['first_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['last_name']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['email']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['password']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['date_of_birth']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['address']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['city']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['state']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"><?php echo $course['zip']; ?> </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        <form action="edit_info.php" method="post">
                                            <input type="hidden" name="email" value="<?php echo $course['email'] ?>" />
                                            <input type="hidden" name="user_id" value="<?php echo $course['user_id'] ?>" />
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
                                    </td> <?php if($_SESSION['sess_user_id'] != $course['user_id']){ ?> <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white"> <a href="drop_user.php?id=<?php echo $course['user_id']; ?>" class="btn_delete">Delete User <svg class="inline h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z" />
                                                <line x1="18" y1="9" x2="12" y2="15" />
                                                <line x1="12" y1="9" x2="18" y2="15" />
                                            </svg></a>
                                    </td> <?php }
                                else {?> <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">You Cannot Delete Yourself</td> <?php } ?>
                                </tr><?php endforeach; ?> </tbody>
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
    <script src="../../JavaScript/hamburger_menu.js"></script>
</body>

</html>