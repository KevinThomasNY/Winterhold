<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Class for Student</title>
    <link rel="shortcut icon" type="image/png" href="../../resources/images/favicon.png" />
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
//chech if student has a hold
function checkHold(){
    include ("../db.php");
    $student_id = $_GET['student_id'];
    $result2 = $db->prepare('select * from student_hold
where student_id = '.$student_id.';');
    $result2->execute();
    $count = $result2->rowCount();
    if($count > 0)
    {
        $err = "Student Has Hold Cannot Register for Class";      
    }
    else{
        $err = "";      
    }
    return $err;
    
}
function checkLevel(){
    include ("../db.php");
    $student_id = $_GET['student_id'];
    $result = $db->query('select * from student where student_id = '.$student_id.'');
    while ($rows = $result->fetch()){
    $student_type = $rows['student_type'];
    }
    return $student_type;
}
function checkCreditLimit(){
    include ("../db.php");
    $student_id = $_GET['student_id'];
    $result = $db->query('select count(crn) as count
    from student_history
    where semester_id = "SEMF2022" and student_id = "'.$student_id.'";');
    while ($rows = $result->fetch()){
    $current_classes = $rows['count'];
    }
    if($current_classes == 4){
        $err = "This student's schedule is currently full. Cannot add another class.";
    }else{
        $err = "";
    }
    return $err;
}
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
        $err .= "Course already taken <br>";
        //Alert Error Message
        ?> <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: 'Error...',
            html: '<?php echo $err; ?>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Go back!',
        }).then(function() {
            window.location = "add_course_student.php";
        })
    </script> <?php
    }
    else{
    //Check prereq Requirements
        $pre_req_id = array();
        $result = $db->query("select * from  prerequite where course_id = '".$course_id."'");
        while ($rows = $result->fetch()){
            $pre_req_id[] = $rows['prereq_id'];
        }
        if ( $pre_req_id == array_intersect($pre_req_id, $course_taken) ){ 
                //prereq meet
                //Time Conflict
                //Get all the crns for fall 2022
                $crns = array();
                $result = $db->query('select * from student_history where student_id = '.$student_id.' and semester_id = "SEMF2022"');
                while ($rows = $result->fetch()){
                    $crns[] = $rows['crn'];
                }
                if(!empty($crns)){
                //Getting students time_slots for Fall 22
                $ts2 = array();
                $result2 = $db->prepare('select * from class_section
                where crn IN (' . implode(',', $crns) . ');');
                $result2->execute();
                while ($rows = $result2->fetch()){
                    //time_slot_id stored in ts2 array
                    $ts2[] = $rows['time_slot_id'];
                }
                //print_r($ts2);
                //check for time_slot intersection
                if(in_array("$time_slot_id",$ts2)){
                    $err .= "Time-Slot Conflict, Student is already taking another class at this time and day <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                } 
                else{
                    //There is no time conflict
                    //check hold
                    //check if undergrad/graduate course
                    //echo "no time conflict";
                    if(checkHold() != ""){
                        $err .= "Student Has a Hold, Cannot Register For A Class. <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                    }else{
                        //Student has no hold; check for undergrad/grad level course
                        if(checkLevel() == "Undergraduate"){
                            $courseDigits = substr($course_id, -4);
                            $courseDigits = (int)$courseDigits;
                            if($courseDigits <= 5999){
                                //Can take class
                                //Check credit limit
                                if(checkCreditLimit() != ""){
                                    $err .= "This student's schedule is currently full. Cannot add another class. <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }else{
                                    //Did not meet credit limit yet
                                    //Insert into student History
                                    #Execute query
                                    $query = "insert into student_history (student_id,crn,semester_id,course_id) values ($student_id,$crn,'SEMF2022','$course_id')";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    //Subtract Seat from class_section.
                                    $result = $db->query("select available_seats from class_section where crn = '$crn'");
                                    while ($rows = $result->fetch()){
                                        $seats = $rows['available_seats'];
                                    }
                                    $seats = $seats - 1;
                                    #Execute query
                                    $query = "update class_section set available_seats = '$seats' where crn = '$crn'";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    #Display sucess message
                                    ?> <script type="text/javascript">
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Class Added to Students Schedule Successfully...',
                                        allowOutsideClick: false,
                                        icon: "success",
                                        html: 'I will close in <b></b> milliseconds.',
                                        timer: 2000,
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
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }
                            }
                            else{
                                //cannot take course
                                $err .= "Cannot take Graduate Course as a Undergraduate Student";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php

                            }
                        }
                        else{
                            $courseDigits = substr($course_id, -4);
                            $courseDigits = (int)$courseDigits;
                            if($courseDigits >= 6000){
                                //Can take class
                                //Check credit limit
                                if(checkCreditLimit() != ""){
                                    $err .= "This student's schedule is currently full. Cannot add another class. <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }else{
                                    //Did not meet credit limit yet
                                    //Insert into student History
                                    //Subtract Seat from class_section
                                    #Execute query
                                    $query = "insert into student_history (student_id,crn,semester_id,course_id) values ($student_id,$crn,'SEMF2022','$course_id')";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    //Subtract Seat from class_section.
                                    $result = $db->query("select available_seats from class_section where crn = '$crn'");
                                    while ($rows = $result->fetch()){
                                        $seats = $rows['available_seats'];
                                    }
                                    $seats = $seats - 1;
                                    #Execute query
                                    $query = "update class_section set available_seats = '$seats' where crn = '$crn'";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    #Display sucess message
                                    ?> <script type="text/javascript">
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Class Added to Students Schedule Successfully...',
                                        allowOutsideClick: false,
                                        icon: "success",
                                        html: 'I will close in <b></b> milliseconds.',
                                        timer: 2000,
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
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php

                                }
                            }
                            else{
                                //cannot take course
                                $err .= "Cannot take Undergraduate Course as a Graduate Student";
                                //Alert Error Message
                                ?> <script type="text/javascript">
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    html: '<?php echo $err; ?>',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: 'Go back!',
                                }).then(function() {
                                    window.location = "add_course_student.php";
                                })
                            </script> <?php
                            }
                        }
                    }
                }               
                }
                else{
                    //Currently Fall 2022 schedule is EMPTY!!!!
                    //Person has not taken a class for Fall 22 yet.
                    //check hold
                    //check if undergrad/graduate course
                    //Check Limit
                    if(checkHold() != ""){
                        $err .= "Student Has a Hold, Cannot Register For A Class. <br>";
                        //Alert Error Message
                        ?> <script type="text/javascript">
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            html: '<?php echo $err; ?>',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            confirmButtonText: 'Go back!',
                        }).then(function() {
                            window.location = "add_course_student.php";
                        })
                    </script> <?php
                    }else{
                        //Student has no hold; check for undergrad/grad level course
                        if(checkLevel() == "Undergraduate"){
                            $courseDigits = substr($course_id, -4);
                            $courseDigits = (int)$courseDigits;
                            if($courseDigits <= 5999){
                                //Can take class
                                //Check credit limit
                                if(checkCreditLimit() != ""){
                                    $err .= "This student's schedule is currently full. Cannot add another class. <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }else{
                                    //Did not meet credit limit yet
                                    //Insert into student History
                                    //Subtract Seat from class_section
                                    #Execute query
                                    $query = "insert into student_history (student_id,crn,semester_id,course_id) values ($student_id,$crn,'SEMF2022','$course_id')";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    //Subtract Seat from class_section.
                                    $result = $db->query("select available_seats from class_section where crn = '$crn'");
                                    while ($rows = $result->fetch()){
                                        $seats = $rows['available_seats'];
                                    }
                                    $seats = $seats - 1;
                                    #Execute query
                                    $query = "update class_section set available_seats = '$seats' where crn = '$crn'";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    #Display sucess message
                                    ?> <script type="text/javascript">
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Class Added to Students Schedule Successfully...',
                                        allowOutsideClick: false,
                                        icon: "success",
                                        html: 'I will close in <b></b> milliseconds.',
                                        timer: 2000,
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
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }
                            }
                            else{
                                //cannot take course
                                $err .= "Cannot take Graduate Course as a Undergraduate Student";
                                //Alert Error Message
                                ?> <script type="text/javascript">
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    html: '<?php echo $err; ?>',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: 'Go back!',
                                }).then(function() {
                                    window.location = "add_course_student.php";
                                })
                            </script> <?php
                            }
                        }
                        else{
                            $courseDigits = substr($course_id, -4);
                            $courseDigits = (int)$courseDigits;
                            if($courseDigits >= 6000){
                                //Can take class
                                //Check credit limit
                                if(checkCreditLimit() != ""){
                                    $err .= "This student's schedule is currently full. Cannot add another class. <br>";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }else{
                                    //Did not meet credit limit yet
                                    //Insert into student History
                                    //Subtract Seat from class_section
                                    #Execute query
                                    $query = "insert into student_history (student_id,crn,semester_id,course_id) values ($student_id,$crn,'SEMF2022','$course_id')";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    //Subtract Seat from class_section.
                                    $result = $db->query("select available_seats from class_section where crn = '$crn'");
                                    while ($rows = $result->fetch()){
                                        $seats = $rows['available_seats'];
                                    }
                                    $seats = $seats - 1;
                                    #Execute query
                                    $query = "update class_section set available_seats = '$seats' where crn = '$crn'";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    #Display sucess message
                                    ?> <script type="text/javascript">
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Class Added to Students Schedule Successfully...',
                                        allowOutsideClick: false,
                                        icon: "success",
                                        html: 'I will close in <b></b> milliseconds.',
                                        timer: 2000,
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
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                                }
                            }
                            else{
                                //cannot take course
                                $err .= "Cannot take Undergraduate Course as a Graduate Student";
                                    //Alert Error Message
                                    ?> <script type="text/javascript">
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        html: '<?php echo $err; ?>',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'Go back!',
                                    }).then(function() {
                                        window.location = "add_course_student.php";
                                    })
                                </script> <?php
                            }
                        }
                    }
                }
        }
        else{
            //prereq not meet
            $err .= "Prerequisite not meet";
            //Alert Error Message
            ?> <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                html: '<?php echo $err; ?>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Go back!',
            }).then(function() {
                window.location = "add_course_student.php";
            })
        </script> <?php
        }
    }

    
}
catch(PDOException $e) {
?> <script type="text/javascript">
    Swal.fire({
        icon: 'error',
        title: 'Error...',
        text: 'Something Went Wrong',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Take me back',
    }).then(function() {
        window.location = "add_course_student.php";
    })
</script> <?php
}
?>