<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\admin\controller;

use app\index\model\Dao\CategoriesDao;
use app\index\model\Dao\LinksDao;
use app\index\model\Dao\SettingsDao;
use app\index\model\Dao\UsersDao;
use app\index\model\Service\BusinessService;
use think\Controller;

class MainSetting extends Controller
{

    public function init(){
        $unit    = new \Unit();
        $timestamp = time();
        $phpSelf  = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
        $this->urlPath  = substr($phpSelf, 1,-4);
        $_GET      = \Unit::stripslashesArray($_GET);
        $_POST     = \Unit::stripslashesArray($_POST);
        $_COOKIE   = \Unit::stripslashesArray($_COOKIE);
        // 获取当前用户
        $this->curUid   = isset($_COOKIE['cur_uid']) ? intval($_COOKIE['cur_uid']) : '';
        $this->curUname = isset($_COOKIE['cur_uname']) ? $_COOKIE['cur_uname'] : '';
        $this->curUcode = isset($_COOKIE['cur_ucode']) ? $_COOKIE['cur_ucode'] : '';
        if($this->curUname && $this->curUid && $this->curUcode){
            $u_key = 'u_' . $this->curUid;

            // 从数据库里读取
            $dbUser = UsersDao::where('id', $this->curUid)->find()->toArray();
            if($dbUser){
                $dbUcode = md5($dbUser['id'] . $dbUser['password'] . $dbUser['regtime'] . $dbUser['lastposttime'] . $dbUser['lastreplytime']);
                if( $this->curUname == $dbUser['name'] && $this->curUcode == $dbUcode){
                    //设置cookie
                    setcookie('cur_uid', $this->curUid, $timestamp+ 86400 * 365, '/');
                    setcookie('cur_uname', $this->curUname, $timestamp+86400 * 365, '/');
                    setcookie('cur_ucode', $this->curUcode, $timestamp+86400 * 365, '/');
                    $this->curUser = $dbUser;
                    unset($dbUser);
                }
            }

        }

        $formhash = $this->formhash($this->curUcode, $this->options);
        // 限制不能打开.php的网址
        if(strpos($_SERVER["REQUEST_URI"], '.php')){
            header('location: /404.html');
            exit('no php script');
        }
    }
    // 获得散列
    function formhash($curUcode, $options) {
        return substr(md5($options['site_create'].$curUcode.'yoursecretwords'), 8, 8);
    }
    public function __construct()
    {
        $this->JGCache = new \JGCache(CURRENT_DIR . '/cache');
        parent::__construct();
        $this->getCache();
        self::init();
        $mtime = explode(' ', microtime());
        $starttime = $mtime[1] + $mtime[0];

        // 限制不能打开.php的网址
        if(strpos($_SERVER["REQUEST_URI"], '.php')){
            header('location: /404.html');
            exit('no php script');
        }

// 只允许注册用户访问
        if($this->options['authorized'] && (!$this->curUser || $this->curUser['flag']<5)){
            if( !in_array($this->urlPath, array('login','logout','sigin','forgot','qqlogin','qqcallback','qqsetname','wblogin','wbcallback','wbsetname'))){
                header('location: /login');
                exit('authorized only');
            }
        }

// 网站暂时关闭
        if($this->options['close'] && (!$this->curUser || $this->curUser['flag']<99)){
            if( !in_array($this->urlPath, array('login','forgot'))){
                header('location: /login');
                exit('site close');
            }
        }


// 获得IP地址
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        $onlineip = addslashes($onlineip);
//if(!$onlineip) exit('error: 400 no ip');

        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($user_agent) {
            $is_spider = preg_match('/(bot|crawl|spider|slurp|sohu-search|lycos|robozilla|google)/i', $user_agent);
            $is_mobie = preg_match('/(Mobile|iPod|iPhone|Android|Opera Mini|BlackBerry|webOS|UCWEB|Blazer|PSP)/i', $user_agent);

            if ($is_mobie) {
                // 设置模板前缀
                $viewat = $_COOKIE['vtpl'];
                if ($viewat=='desktop') {
                    $tpl = '';
                } else {
                    $tpl = 'ios_';
                }
            } else {
                $tpl = '';
            }
        } else {
            //exit('error: 400 no agent');
            $is_spider = '';
            $is_mobie = '';
        }
        $this->tpl = $tpl;
        //最近添加的节点
        $newest_nodes = $this->get_newest_nodes();
        //友好链接
        $links = $this->get_links();
        $site_infos = $this->get_site_infos();
        $this->assign("tpl", $this->tpl);
        $this->assign("options", $this->options);
        $this->assign("cur_user", $this->curUser);
        $this->assign("url_path", $this->urlPath);
        $this->assign("starttime", $starttime);
        $this->assign("newest_nodes", $newest_nodes);
        $this->assign("links", $links);
        $this->assign("site_infos", $site_infos);
    }

    /** 获取网站基本信息
     *
     */
    public function getCache(){
        //获取网站基本配置 $options
        $options = $this->JGCache->get('site_options');
        if($options === FALSE){
            $settings = SettingsDao::all();
            $options = array();
            foreach ($settings AS $setting){
                $options[$setting['title']] = $setting['value'];
            }

            // 检测新增的 site_create
            if($options['site_create']=='0'){
                $mObj = UsersDao::find(1);
                if($mObj){
                    $site_create = $mObj['regtime'];
                    SettingsDao::where('title', 'site_create')->update(['value' => $site_create]);
                    $options['site_create'] = $site_create;
                }
            }

            $options = \Unit::stripslashesArray($options);

            if(!$options['safe_imgdomain']){
                $options['safe_imgdomain'] = $_SERVER['HTTP_HOST'];
            }

            unset($setting);

            //
            $this->JGCache->set('site_options', $options);
        }
        $this->options  = $options;
    }
    // 获取最新添加的分类
    public function get_newest_nodes() {
        $node_arr =  $this->JGCache->get('new_nodes');
        if($node_arr === FALSE){
            $nodes = CategoriesDao::order("id desc")->limit($this->options['newest_node_num'])->select();
            $node_arr = array();
            foreach ($nodes AS $node) {
                $node_arr['nodes/'.$node['id']] = $node['name'];
            }
            unset($node);
            $this->JGCache->set('new_nodes', $node_arr);
        }
        return $node_arr;
    }

    //获取链接
    function get_links() {
        $links = $this->JGCache->get('site_links');
        if($links === FALSE){
            $linkInfos = LinksDao::select();
            $links = array();
            foreach ($linkInfos AS $link) {
                $links[$link['name']] = $link['url'];
            }

            unset($link);

            $this->JGCache->set('site_links', $links);
        }
        return $links;
    }

    // 获取站点信息
    function get_site_infos() {
        $site_infos = $this->JGCache->get('site_infos');
        if($site_infos === FALSE){
            // 如果删除表里的数据则下面信息不准确
            $site_infos = array();
            $businessService = new BusinessService();
            $table_status    = $businessService->getUsersStatus();
            $site_infos['注册会员'] = $table_status[0]['Auto_increment'] -1;

            $table_status    = $businessService->getCategoriesStatus();
            $site_infos['节点'] = $table_status[0]['Auto_increment'] -1;

            $table_status    = $businessService->getArticlesStatus();
            $site_infos['主题'] = $table_status[0]['Auto_increment'] -1;

            $table_status    = $businessService->getCommentsStatus();
            $site_infos['回复'] = $table_status[0]['Auto_increment'] -1;

            $this->JGCache->set('site_infos', $site_infos);
        }

        return $site_infos;

    }
}
