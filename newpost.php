<?php
define('IN_SAESPOT', 1);
define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));

include(CURRENT_DIR . '/config.php');
include(CURRENT_DIR . '/common.php');

if (!$cur_user) exit('error: 401 login please');
if ($cur_user['flag']==0){
    exit('error: 403 Access Denied');
}else if($cur_user['flag']==1){
    exit('error: 401 Access Denied');
}

$cid = intval($_GET['cid']);
if($cid<1){
    header('location: /');
    exit;
}

if($options['main_nodes']){
    $main_nodes_arr = explode(",", $options['main_nodes']);
    if(!in_array($cid, $main_nodes_arr)){
       $main_nodes_arr[] = $cid;
    }
    $main_nodes_str = implode(",", $main_nodes_arr);
    $query = $DBS->query("SELECT `id`, `name` FROM `yunbbs_categories` WHERE `id` in($main_nodes_str)");
    
    $main_nodes_arr = array();
    while($node = $DBS->fetch_array($query)) {
        $main_nodes_arr[$node['id']] = $node['name'];
    }
    
    unset($node);
    $DBS->free_result($query);
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($_SERVER['HTTP_REFERER']) || $_POST['formhash'] != formhash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
    	exit('403: unknown referer.');
    }
    
    $p_title = addslashes(trim($_POST['title']));
    $p_content = addslashes(trim($_POST['content']));
    $p_tags = gettags(trim($_POST['tags']));
    
    // spam_words
    if($options['spam_words'] && $cur_user['flag']<99){
        $check_con = ' '.$p_title.$p_content;
        $spam_words_arr = explode(",", $options['spam_words']);
        foreach($spam_words_arr as $spam){
            if(strpos($check_con, $spam)){
                // has spam word
                $DBS->unbuffered_query("UPDATE yunbbs_users SET flag='0' WHERE id='$cur_uid'");
                
                exit('403: dont post any spam.');
            }
        }
    }
    
    if($options['main_nodes']){
        $cid = $_POST['select_cid'];
    }
    if(($timestamp - $cur_user['lastposttime']) > $options['article_post_space']){
        if($p_title){
            if(mb_strlen($p_title,'utf-8')<=$options['article_title_max_len'] && mb_strlen($p_content,'utf-8')<=$options['article_content_max_len']){
                $p_title = htmlspecialchars($p_title);
                $p_content = htmlspecialchars($p_content);
                $p_tags = htmlspecialchars($p_tags);
                $DBS->query("INSERT INTO yunbbs_articles (id,cid,uid,title,content,tags,addtime,edittime) VALUES (null,$cid,$cur_uid, '$p_title', '$p_content', '$p_tags', $timestamp, $timestamp)");
                $new_aid = $DBS->insert_id();
                $DBS->unbuffered_query("UPDATE yunbbs_categories SET articles=articles+1 WHERE id='$cid'");
                $DBS->unbuffered_query("UPDATE yunbbs_users SET articles=articles+1, lastposttime=$timestamp WHERE id='$cur_uid'");
                // set tags
                if($p_tags){
                    $newadd_tags = explode(",", $p_tags);
                    foreach($newadd_tags as $tag){
                        $tag_obj  = $DBS->fetch_one_array("SELECT `id`,`articles`,`ids` FROM `yunbbs_tags` WHERE `name`='$tag'");
                        if(empty($tag_obj)) {
                            $DBS->query("INSERT INTO `yunbbs_tags` (`id`,`name`,`articles`,`ids`) VALUES (null,'$tag', '1', '$new_aid')");
                        } else {
                            if($tag_obj['ids']){
                                $ids_arr = explode(",", $tag_obj['ids']);
                                if(!in_array($new_aid, $ids_arr)){
                                    $ids = $new_aid.','.$tag_obj['ids'];
                                    $DBS->unbuffered_query("UPDATE `yunbbs_tags` SET `articles`=`articles`+1, `ids`='$ids' WHERE `name`='$tag'");
                                }
                            }else{
                                $ids = $new_aid;
                                $DBS->unbuffered_query("UPDATE `yunbbs_tags` SET `articles`=`articles`+1, `ids`='$ids' WHERE `name`='$tag'");
                            }
                        }
                    }
                }
                // 更新u_code
                $cur_user['lastposttime'] = $timestamp;
                //
                $new_ucode = md5($cur_uid.$cur_user['password'].$cur_user['regtime'].$cur_user['lastposttime'].$cur_user['lastreplytime']);
                setcookie("cur_uid", $cur_uid, $timestamp+ 86400 * 365, '/');
                setcookie("cur_uname", $cur_uname, $timestamp+86400 * 365, '/');
                setcookie("cur_ucode", $new_ucode, $timestamp+86400 * 365, '/');
                
                // mentions 没有提醒用户的id
                $mentions = find_mentions(' '.$p_title.' '.$p_content, $cur_uname);
                if($mentions && count($mentions)<=10){
                    foreach($mentions as $m_name){
                        $DBS->unbuffered_query("UPDATE yunbbs_users SET notic =  concat('$new_aid,', notic) WHERE name='$m_name'");
                    }
                }
                
                // cache
                $cache->mdel(array('home_articledb', 'hot_nodes', 'site_infos'));
                
                $p_title = $p_content = $p_tags = '';
                header('location: /topics/'.$new_aid);
                exit;
            }else{
                $tip = '标题'.mb_strlen($p_title,'utf-8').' 或 内容'.mb_strlen($p_content,'utf-8').' 太长了';
            }
        }else{
            $tip = '标题 不能留空';
        }
    }else{
        $tip = '发帖最小间隔时间是 '.$options['article_post_space'].'秒';
    }
}else{
    $p_title = '';
    $p_content = '';
    $p_tags = '';
    $tip = '';
    $c_obj = $DBS->fetch_one_array("SELECT * FROM yunbbs_categories WHERE id='".$cid."'");
    if(!$c_obj){
        exit('error: 404');
    }
}
// 页面变量
$title = '发新帖子';
// 设置处理图片的最大宽度
$img_max_w = 650;
$newpost_page = '1';

$pagefile = CURRENT_DIR . '/templates/default/'.$tpl.'newpost.php';

include(CURRENT_DIR . '/templates/default/'.$tpl.'layout.php');

?>
