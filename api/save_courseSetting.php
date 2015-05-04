<?php
// get data from parameter

echo(json_encode($_POST, JSON_UNESCAPED_UNICODE));


// do saving on mysql

// do saving on mongo



// alert saving status
echo '<script>alert("課程設定儲存成功！");</script>';
exit;

// redirect to main page



?>