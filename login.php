<?php
define('IN_SAESPOT', 1);
define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));

include(CURRENT_DIR . '/config.php');
include(CURRENT_DIR . '/common.php');
require_once(CURRENT_DIR . '/include/GoogleAuth/GoogleAuth.php');

/*
// 屏蔽下面几行可以通过 用户名和密码 登录
if(($options['qq_appid'] && $options['qq_appkey']) || ($options['wb_key'] && $options['wb_secret'])){
    header("content-Type: text/html; charset=UTF-8");
    echo '请用 ';
    if($options['wb_key'] && $options['wb_secret']){
        echo '&nbsp;<a href="/wblogin">微博登录</a>';
    }
    if($options['qq_appid'] && $options['qq_appkey']){
        echo '&nbsp;<a href="/qqlogin">QQ登录</a>';
    }
    echo '&nbsp;<a href="/">返回首页</a>';
    exit;
}

*/

if($cur_user){
    // 如果已经登录用户无聊打开这网址就让他重新登录吧
    setcookie("cur_uid", '', $timestamp-86400 * 365, '/');
    setcookie("cur_uname", '', $timestamp-86400 * 365, '/');
    setcookie("cur_ucode", '', $timestamp-86400 * 365, '/');
    $cur_user = null;
    $cur_uid = '';
}

$name = '';
$errors = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //if(empty($_SERVER['HTTP_REFERER']) || $_POST['formhash'] != formhash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
    	//exit('403: unknown referer.');
   // }
    
    $name = addslashes(strtolower(trim($_POST["name"])));
    $pw = addslashes(trim($_POST["pw"]));
    $gcode = $_POST["gauth"];

    $seccode = intval(trim($_POST["seccode"]));
    if($name && $pw && $seccode){
        if(strlen($name)<21 && strlen($pw)<32){
            if(preg_match('/^[a-zA-Z0-9\x80-\xff]{4,20}$/i', $name)){
                if(preg_match('/^[0-9]{4,20}$/', $name)){
                    $errors[] = '名字不能全为数字';
                }else{
                    error_reporting(0);
                    session_start();
                    if($seccode === intval($_SESSION['code'])){
                        $db_user = $DBS->fetch_one_array("SELECT * FROM yunbbs_users WHERE name='".$name."' LIMIT 1");
                        if($db_user){
                            $pwmd5 = encode_password($pw, $db_user['regtime']);
                            if($pwmd5 == $db_user['password']){
                                
                                // G Auth Checking
                                
                                $gsecret = $db_user['gauthsecret'];

                                if ($gsecret != Null){
                                    if ($gcode){

                                        $ga = new GoogleAuth();
                                        $checkResult = $ga->verifyCode($gsecret, $gcode);

                                        if ($checkResult) {
                                            //设置cookie
                                            $db_ucode = md5($db_user['id'].$db_user['password'].$db_user['regtime'].$db_user['lastposttime'].$db_user['lastreplytime']);
                                            $cur_uid = $db_user['id'];
                                            
                                            setcookie("cur_uid", $cur_uid, time()+ 86400 * 365, '/');
                                            setcookie("cur_uname", $name, time()+86400 * 365, '/');
                                            setcookie("cur_ucode", $db_ucode, time()+86400 * 365, '/');
                                            $cur_user = $db_user;
                                            unset($db_user);
                                            
                                            header('location: /');
                                            exit('logined');
                                        } else {
                                            $errors[] = '安全码已过期或输入不正确!';
                                        }
                                        
                                    }else{
                                        $errors[] = '您已开启二次验证，请输入安全码!';
                                    }

                                }else{
                                    //设置cookie
                                    $db_ucode = md5($db_user['id'].$db_user['password'].$db_user['regtime'].$db_user['lastposttime'].$db_user['lastreplytime']);
                                    $cur_uid = $db_user['id'];
                                    
                                    setcookie("cur_uid", $cur_uid, time()+ 86400 * 365, '/');
                                    setcookie("cur_uname", $name, time()+86400 * 365, '/');
                                    setcookie("cur_ucode", $db_ucode, time()+86400 * 365, '/');
                                    $cur_user = $db_user;
                                    unset($db_user);
                                    
                                    header('location: /');
                                    exit('logined');
                                }

                            }else{
                                // 用户名和密码不匹配
                                $errors[] = '输入的用户名或密码不正确!';
                            }

                        }else{
                            // 没有该用户名
                            $errors[] = '输入的用户名不正确!';
                        }
                    }else{
                        $errors[] = '输入的验证码不正确！';
                    }
                }
            }else{
                $errors[] = '名字太长或太短或包含非法字符！';
            }
        }else{
            $errors[] = '用户名或密码太长了！';
        }
    }else{
       $errors[] = '用户名、密码、验证码必填！'; 
    }
}

// 页面变量
$title = '登 录';

$pagefile = CURRENT_DIR . '/templates/default/'.$tpl.'sigin_login.php';

include(CURRENT_DIR . '/templates/default/'.$tpl.'layout.php');

?>
