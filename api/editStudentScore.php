<!-- 編輯學生分數 -->
<?php
    include_once('auth.php');
    include_once("../mysql.php");

    $course_id = $_POST['course_id'];
    $student_id = $_POST['student_id'];
    $score = $_POST['score'];
    $isFinish = $_POST['isFinish'];


    $updateMYSQL = "UPDATE attendent 
                    SET `score` = '$score',
                    `is_finish` = '$isFinish' 
                    WHERE course_id=$course_id
                    AND member_id = $student_id";

    $mysqlResult = mysql_query($updateMYSQL);

    if($mysqlResult){
        $response = array(
            'status' => 'ok',
        );  
    }
    else{
        $response = array(
            'status' => 'error'
        );
    }

    echo json_encode($response);

?>