<?php /*a:1:{s:72:"/users/sftc/work/code/youbbs/application/index/view/index/gototopic.html";i:1664338617;}*/ ?>
<?php

$tid = $_GET['tid'];
$db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE id='".$cur_uid."' LIMIT 1");

if($db_user['notic']){
    $n_arr = explode(',', $db_user['notic']);
    foreach($n_arr as $k=>$v){
        if($v == $tid){
            unset($n_arr[$k]);
        }
    }
    $new_notic = implode(',', $n_arr);
    $DBS->unbuffered_query("UPDATE yunbbs_users SET notic = '$new_notic' WHERE id='$cur_uid'");
    
    unset($n_arr);
    unset($new_notic);
}
header('location: /new/topics/'.$tid);
exit;
?>
