<?php
//$result = shell_exec("echo \"test\" | agrep -1 \"text\"");
$result = shell_exec("echo \"must\" | agrep -1 \"must\"");
var_dump($result);
?>
