<?php /*a:1:{s:73:"/users/sftc/work/code/youbbs/application/index/view/index/admin_node.html";i:1664199006;}*/ ?>
<?php
#define('IN_SAESPOT', 1);
#define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));


if (!$cur_user || $cur_user['flag']<99) exit('error: 403 Access Denied');

$nid = isset($_GET['nid']) ?intval($_GET['nid']) : 0;
$c_obj = [];
if($nid){
    $query = "SELECT * FROM yunbbs_categories WHERE id='$nid'";
    $c_obj = $DBS->fetch_one_array($query);
    if(!$c_obj){
        header('location: /admin-node#edit');
        exit;
    }
}

$tip1 = '';
$tip2 = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    
    if($action=='find'){
        $n_id = trim($_POST['findid']);
        if($n_id){
            header('location: /admin-node-'.$n_id);
        }else{
            header('location: /admin-node#edit');
        }
        exit;
    }else if($action=='add'){
        $n_name = trim($_POST['name']);
        $n_about = trim($_POST['about']);
        if($n_name){
            $check_obj = $DBS->fetch_one_array("SELECT * FROM yunbbs_categories WHERE name='".$n_name."'");
            if($check_obj){
                $tip1 = $n_name.' 分类名已存在，请修改为不同的分类名';
            }else{
                if($DBS->query("INSERT INTO yunbbs_categories (id,name,about) VALUES (null,'$n_name','$n_about')")){
                    $tip1 = '已成功添加';
                    $cache->mdel(array('new_nodes', 'hot_nodes', 'site_infos'));
                }else{
                    $tip1 = '数据库更新失败，修改尚未保存，请稍后再试';
                }
            }
        }else{
            $tip1 = '分类名不能留空';
        }
    }else if($action=='edit'){
        $n_name = trim($_POST['name']);
        $n_about = trim($_POST['about']);
        if($n_name){
            if($DBS->unbuffered_query("UPDATE yunbbs_categories SET name='$n_name',about='$n_about' WHERE id='$nid'")){
                $c_obj['name'] = $n_name;
                $c_obj['about'] = $n_about;
                $tip2 = '已成功保存';
                $cache->mdel(array('new_nodes', 'hot_nodes'));
            }else{
                $tip2 = '数据库更新失败，修改尚未保存，请稍后再试';
            }
        }else{
            $tip2 = '分类名不能留空';
        }
        
    }

}

// 页面变量
$title = '分类管理';

$pagefile = $current_dir . '/../../../../templates/default/'.$tpl.'admin-node.php';

include($current_dir . '/../../../../templates/default/'.$tpl.'layout.php');
?>
