<?php
//一些常用的数据操作

if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied');

//获取网站基本配置 $options
global $options;
 $options = $cache->get('site_options');
if($options === FALSE){
    $query = $DBS->query("SELECT `title`, `value` FROM `yunbbs_settings`");
    $options = array();
    while($setting = $DBS->fetch_array($query)) {
        $options[$setting['title']] = $setting['value'];
    }
    
    // 检测新增的 site_create
    if($options['site_create']=='0'){
        $query = "SELECT `regtime` FROM `yunbbs_users` WHERE `id`='1'";
        $m_obj = $DBS->fetch_one_array($query);
        if($m_obj){
            $site_create = $m_obj['regtime'];
            $DBS->query("UPDATE `yunbbs_settings` SET `value` = '$site_create' WHERE `title` = 'site_create' LIMIT 1");
            $options['site_create'] = $site_create;
        }
    }

    $options = stripslashes_array($options);

    if(!$options['safe_imgdomain']){
        $options['safe_imgdomain'] = $_SERVER['HTTP_HOST'];
    }
    unset($setting);
    $DBS->free_result($query);

    //
    $cache->set('site_options', $options);
}



//获取链接
function get_links() {
    global $cache;
    $links = $cache->get('site_links');
    if($links === FALSE){
        global $DBS;
        $query = $DBS->query("SELECT `name`, `url` FROM `yunbbs_links`");
        $links = array();
        while($link = $DBS->fetch_array($query)) {
            $links[$link['name']] = $link['url'];
        }
        
        unset($link);
        $DBS->free_result($query);
        
        $cache->set('site_links', $links);
    }
    return $links;
}

// 获取最新添加的分类
function get_newest_nodes() {
    global $cache;
    $node_arr = $cache->get('new_nodes');
    if($node_arr === FALSE){
        global $DBS, $options;
        $query = $DBS->query("SELECT `id`, `name`, `articles` FROM `yunbbs_categories` ORDER BY  `id` DESC LIMIT ".$options['newest_node_num']);
        $node_arr = array();
        while($node = $DBS->fetch_array($query)) {
            $node_arr['new/nodes/'.$node['id']] = $node['name'];
        }
        unset($node);
        $DBS->free_result($query);
        
        $cache->set('new_nodes', $node_arr);
    }
    return $node_arr;
}

// 获取热门分类
function get_bot_nodes() {
    global $cache;
    $node_arr = $cache->get('hot_nodes');
    if($node_arr === FALSE){
        global $DBS, $options;
        $query = $DBS->query("SELECT `id`, `name`, `articles` FROM `yunbbs_categories` ORDER BY  `articles` DESC LIMIT ".$options['bot_node_num']);
        $node_arr = array();
        while($node = $DBS->fetch_array($query)) {
            $node_arr['nodes/'.$node['id']] = $node['name'];
        }
        unset($node);
        $DBS->free_result($query);
        
        $cache->set('hot_nodes', $node_arr);
    }
    return $node_arr;
}

// 获取站点信息
function get_site_infos() {
    global $cache;
    $site_infos = $cache->get('site_infos');
    if($site_infos === FALSE){
        global $DBS;
        // 如果删除表里的数据则下面信息不准确
        $site_infos = array();
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_users'");
        $site_infos['注册会员'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_categories'");
        $site_infos['节点'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_articles'");
        $site_infos['主题'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_comments'");
        $site_infos['回复'] = $table_status['Auto_increment'] -1;
        
        $cache->set('site_infos', $site_infos);
    }
    
    return $site_infos;

}

?>
