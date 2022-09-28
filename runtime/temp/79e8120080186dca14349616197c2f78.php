<?php /*a:1:{s:69:"/users/sftc/work/code/youbbs/application/index/view/index/logout.html";i:1664246945;}*/ ?>
<?php

if($cur_user){
    setcookie("cur_uid", '', $timestamp-86400 * 365, '/');
    setcookie("cur_uname", '', $timestamp-86400 * 365, '/');
    setcookie("cur_ucode", '', $timestamp-86400 * 365, '/');
    header('location: /new/');
}else{
    header('location: /new/');
}
exit;

?>
