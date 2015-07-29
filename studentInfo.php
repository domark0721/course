<?php
    include_once('api/auth.php');
    include_once("mongodb.php");
    include_once("mysql.php");
    include_once('api/isLogin.php');

    session_start();
    $_SESSION['url'] = $_SERVER['REQUEST_URI']; 
    
    $course_id = $_GET['course_id'];
    $student_id = $_GET['student_id'];

    //courseMetaData
    $sql = "SELECT * from course WHERE course_id='$course_id'";
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result)){
        $courseMetadata = $row;
        break;
    } 

    // studentData from mysql
    $sql = "SELECT * from attendent as a
            LEFT JOIN member as b ON a.member_id = b.member_id
            where course_id='$course_id'
            AND a.member_id = '$student_id'";
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result)){
        $studentData = $row;
        break;
    }   

    
?>
<!doctype html>
<html>
    <head>
        <?php require("meta_com.php"); ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link type="text/css" rel="stylesheet" href="css/courseSetting.css">
        <link type="text/css" rel="stylesheet" href="css/studentManage.css">
        <link type="text/css" rel="stylesheet" href="css/studentInfo.css">
        <title>學生詳細資料 - <?php echo $courseMetadata['course_name']; ?> - NUCourse</title>
    </head> 
    <body>
        <div class="totalWrapper">
            <?php require("header.php"); ?>
            <div class="container">
                <div id="topBanner_wrap">
                    <div class="content-wrap clearfix">
                        <div class="editorBarIcon"><i class="fa fa-book fa_plus"></i></div>
                        <div class="courseHeader">
                            <div class="topBanner_Title">學生詳細資料</div>
                            <div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="studentList-wrap">
                    <div class="studentList_container">
                    <?php
                        if(!empty($studentData)){
                                $memberID = sprintf("%03d", $studentData['member_id']);
                                
                                if(!empty($studentData['score'])){
                                    $score = $studentData['score'];
                                } else { 
                                    $score = '';
                                }
                                
                                $joinDate = explode(' ', $studentData['create_date']);

                    ?>
                        <div class="student_info">
                            <div class="id">
                                <span><?php echo $memberID;?></span>
                            </div>
                            <div class="name"><?php echo $studentData['name'];?></div>
                            <div class="joinDate">加入日期: <?php echo $joinDate[0];?></div>
                            <div class="totalScore">
                                <label>總成績: 
                                <input id="scoreInput" type="text" value="<?php echo $score;?>" size="4"></label>
                            </div>
                            <div class="isFinish">
                                修課狀態:
                                <span class="finishRadioBtn">
                                    <input id="notFinish" type="radio" name="finish" value="0" <?php if($studentData['is_finish']=='0'){echo "checked";}?>>
                                    <label for="notFinish">修習中</label>

                                    <input id="isFinish" type="radio" name="finish" value="1" <?php if($studentData['is_finish']=='1'){echo "checked";}?>>
                                    <label for="isFinish">已修畢</label>
                                </span>                                
                            </div>
                        </div>                          
                        
                <?php }else{ ?>
                        <div class="student_item">
                            <a class="no_student">--- 無該學生資料 ---</a>
                        </div>                                                          
                <?php }?>
                    </div>
                    <div class="resultBtn">
                        <a class="panelBtn save" id="saveStudentInfo">儲存</a>
                        <a class="panelBtn giveup" href="studentManage.php?course_id=<?php echo $course_id;?>">返 回</a>
                    </div>  
                </div>
            </div>
            <input id="param_course_id" value="<?php echo $course_id;?>" type="hidden">
            <input id="param_student_id" value="<?php echo $student_id;?>" type="hidden">

            <?php require("footer.php"); ?>
        </div>
        <?php require("js/js_com.php"); ?>
        <script type="text/javascript" src="js/studentInfo.js"></script>
    </body>
</html>