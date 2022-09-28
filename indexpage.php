<?php
//todo 以迁移
define('IN_SAESPOT', 1);
define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));

include(CURRENT_DIR . '/config.php');
include(CURRENT_DIR . '/common.php');

$page = intval($_GET['page']);

// 处理正确的页数
$table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_articles'");
$taltol_article = $table_status['Auto_increment'] -1;
$taltol_page = ceil($taltol_article/$options['list_shownum']);
if($page<0){
    header('location: /');
    exit;
}else if($page==1){
    header('location: /');
    exit;
}else{
    if($page>$taltol_page){
        header('location: /page/'.$taltol_page);
        exit;
    }
}

// 获取最近文章列表
if($page == 0) $page = 1;

$query_sql = "SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.addtime,a.edittime,a.comments,a.isred,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
    FROM `yunbbs_articles` a 
    LEFT JOIN `yunbbs_categories` c ON c.id=a.cid
    LEFT JOIN `yunbbs_users` u ON a.uid=u.id
    LEFT JOIN `yunbbs_users` ru ON a.ruid=ru.id
	WHERE `cid` > '1'
    ORDER BY `edittime` DESC LIMIT ".($page-1)*$options['list_shownum'].",".$options['list_shownum'];
$query = $DBS->query($query_sql);
$articledb=array();
while ($article = $DBS->fetch_array($query)) {
    // 格式化内容
    if($article['isred'] == '1'){
         $article['title'] = "<span class=\"label label-success\">推荐</span>".$article['title'];
     }
    $article['addtime'] = showtime($article['addtime']);
    $article['edittime'] = showtime($article['edittime']);
    $articledb[] = $article;
}
unset($article);
$DBS->free_result($query);


// 页面变量
$title = $options['name'].' - page '.$page;

$site_infos = get_site_infos();
$newest_nodes = get_newest_nodes();
if(count($newest_nodes)==$options['newest_node_num']){
    $bot_nodes = get_bot_nodes();
}

$show_sider_ad = "1";
//$links = get_links();
$meta_kws = htmlspecialchars(mb_substr($options['name'], 0, 6, 'utf-8'));
if($options['site_des']){
    $meta_des = htmlspecialchars(mb_substr($options['site_des'], 0, 150, 'utf-8')).' - page '.$page;
}

$pagefile = CURRENT_DIR . '/templates/default/'.$tpl.'indexpage.php';

include(CURRENT_DIR . '/templates/default/'.$tpl.'layout.php');

?>
