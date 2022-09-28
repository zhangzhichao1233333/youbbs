<?php
namespace app\admin\controller;


use app\admin\controller\MainSetting;
use app\index\model\Dao\CategoriesDao;
use app\index\model\Service\BusinessService;

class Node extends BaseAction
{
    public $articles = [];
    public $nid;
    public function init() {
        if (!$this->curUser || $this->curUser['flag']<99) exit('error: 403 Access Denied');
        if (isset($_GET['nid'])) {
            $this->nid = intval($_GET['nid']);
            if($this->nid){
                $this->articles = CategoriesDao::where(['id' => $this->nid])->find()->toArray();
                if(!$this->articles){
                    header('location: /admin-node#edit');
                    exit;
                }
            }
        }
    }
    public function index()
    {
        parent::__construct();
        $this->init();
        $tip1 = '';
        $tip2 = '';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'];
            if ($action=='find') {
                $this->find();
            } else if ($action=='add') {
                $tip1 = $this->add();
            }else if($action=='edit'){
                $tip2 = $this->edit();
            }
        }
        // 页面变量
        $title = '分类管理';
        $this->assign('title', $title);
        $this->assign('c_obj', $this->articles);
        $this->assign('tip1', $tip1);
        $this->assign('tip2', $tip2);
        $this->assign('articlesNum', count($this->articles));
        return $this->fetch();
    }
    public function find() {

        $n_id = trim($_POST['findid']);

        if($n_id){
            header('location: /admin/node?nid='.$n_id);
        }else{
            header('location: /admin/node#edit');
        }
    }

    public function add() {
        $n_name = trim($_POST['name']);
        $n_about = trim($_POST['about']);
        if ($n_name) {
            $check_obj = CategoriesDao::where('name', $n_name)->find();
            #$check_obj = $DBS->fetch_one_array("SELECT * FROM yunbbs_categories WHERE name='".$n_name."'");
            if ($check_obj) {
                $tip1 = $n_name.' 分类名已存在，请修改为不同的分类名';
            } else {
                $data = ['name' => $n_name, 'about' => $n_about];
                if (CategoriesDao::insert($data)) {
                    $tip1 = '已成功添加';
                    $this->JGCache->mdel(array('new_nodes', 'hot_nodes', 'site_infos'));
                } else{
                    $tip1 = '数据库更新失败，修改尚未保存，请稍后再试';
                }
            }
        }else{
            $tip1 = '分类名不能留空';
        }
        return $tip1;
    }

    public function edit() {
        $n_name = trim($_POST['name']);
        $n_about = trim($_POST['about']);
        if ($n_name) {
            if (CategoriesDao::where("id", $this->nid)->update(['name'=> $n_name, 'about' => $n_about])) {
                $this->articles['name'] = $n_name;
                $this->articles['about'] = $n_about;
                $tip2 = '已成功保存';
                $this->JGCache->mdel(array('new_nodes', 'hot_nodes'));
            }else{
                $tip2 = '数据库更新失败，修改尚未保存，请稍后再试';
            }
        }else{
            $tip2 = '分类名不能留空';
        }
        return $tip2;
    }
}
