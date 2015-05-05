<?php

// get teacher member info (member_id from session) id, name


// add a new course to db
// 1. MySQL: insert row in `course` table (teacher_id, teacher_name and some default value)
$course_id = ......


// 2. get course_id from step 1 (mysql), then insert row into MongoDB * with initial course data *

$mongoInitData = array(
    "couser_id"=> $cousr_id,
    .....

    "content" => array(

    )
)

// Mongo Initial Course Data
 //   "course_id" : {get from MySQL},
 //    "description" : "",
 //    "syllabus" : "",
 //    "teachingMethods" : "",
 //    "textbooks" : "",
 //    "references" : "",
 //    "content" : {
 //        "chapters" : [ ]
//     }  


// redirect to course setting (pass course_id as parameter)

?>