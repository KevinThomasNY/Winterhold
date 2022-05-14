<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Hold</title>
    <link
      rel="shortcut icon"
      type="image/png"
      href="../../resources/images/favicon.png"
    />
    <link rel="stylesheet" href="../../css/home.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
</body>

</html> <?php
session_start();
include ("../db.php");
if (isset($_SESSION['sess_user_id']) && $_SESSION['sess_user_id'] != "") {
    #echo '<h1>Welcome '.$_SESSION['sess_first_name']. " " .$_SESSION['sess_last_name']. '</h1>';
    
} else {
    header('location:../login.php');
}
$student_id = $_GET['student_id'];
$crn = $_GET['crn'];
$err = "";
try {
    //Get the course details
    $result = $db->query('select * from class_section where crn = '.$crn.'');
    while ($rows = $result->fetch()){
        $course_name = $rows['course_name'];
        $time_slot_id = $rows['time_slot_id'];
    } 
    //Get course_id
    $result = $db->query('select course_id from course where course_name = "'.$course_name.'"');
    while ($rows = $result->fetch()){
    $course_id = $rows['course_id'];
    }
    //Get all the courses the student has taken
    $course_taken = array();
    $result = $db->query('select * from student_history where student_id = '.$student_id.'');
    while ($rows = $result->fetch()){
        $course_taken[] = $rows['course_id'];
    }
    if(in_array("$course_id",$course_taken)){
        $err = "course already taken <br>";
    }
    // else{
    // //Check prereq Requirements
    //     $pre_req_id = array();
    //     $result = $db->query("select * from  prerequite where course_id = '".$course_id."'");
    //     while ($rows = $result->fetch()){
    //         $pre_req_id[] = $rows['prereq_id'];
    //     }
    //     if ( $pre_req_id == array_intersect($pre_req_id, $course_taken) ) {
    //             //Time Conflict
    //             //Get all the crns for fall 2022
    //             $crns = array();
    //             $result = $db->query('select * from student_history where student_id = '.$student_id.' and semester_id = "SEMS2022"');
    //             while ($rows = $result->fetch()){
    //                 $crns[] = $rows['crn'];
    //             }
    //             if(!empty($crns)){
    //                 echo " adsf";
    //             $ts2 = array();
    //             $result2 = $db->prepare('select * from class_section
    //             where crn IN (' . implode(',', $crns) . ');');
    //             $result->execute();
    //             $count = $result->rowCount();
    //             if($count == 0){
    //                 echo "lol";
    //             }
    //             else{
    //                 while ($rows = $result->fetch()){
    //                     $ts2[] = $rows['time_slot_id'];
    //                 }
    //                 print_r($ts2);
    //             }
    //             }
    //             else{
    //                 //check hold
    //             }
    //             // $query_ts = "select * from class_section
    //             // where crn IN (" . implode(',', $crns) . ")";
    //             // $ts_statement = $db->prepare($query_ts);
    //             // $ts_statement->execute();

    //     }
    //     else{
    //         $err .= "prereq not meet";
    //     }
    // }

    //Hold Conflict
    // $query = "insert into student_hold (student_id,hold_id,date_added) value ($user_id,$hold_id,'$date')";
    // $stmt = $db->prepare($query);
    // $stmt->execute();
    // header('location:./student_hold.php');
    
}
catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'You cannot add the same type of hold to a student',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back to add hold!',
    }).then(function() {
        window.location = "addHold.php";
    })
</script> <?php
}
?>