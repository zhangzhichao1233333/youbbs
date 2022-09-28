<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\index\controller;



use app\index\controller\Base\Base;
use think\facade\Request;

class Index extends Base
{
    /**首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**收藏
     * @return mixed
     */
    public function favorites()
    {
        return $this->fetch();
    }

    /**设置
     * @return mixed
     */
    public function setting()
    {
        return $this->fetch();
    }
    /**退出
     * @return mixed
     */
    public function logout()
    {
        return $this->fetch();
    }
    /**登录
     * @return mixed
     */
    public function login()
    {
        return $this->fetch();
    }
    /**用户信息
     * @return mixed
     */
    public function member()
    {
        $mid =Request::instance()->param('mid');
        $_GET['mid'] = $mid;
        return $this->fetch();
    }

    /**创建新主题
     * @return mixed
     */
    public function newpost()
    {
        $cid =Request::instance()->param('cid');
        $_GET['cid'] = $cid;
        return $this->fetch();
    }
    /**创建新主题
     * @return mixed
     */
    public function notifications()
    {
        return $this->fetch();
    }
    /**最近添加的节点
     * @return mixed
     */
    public function nodepage()
    {
        $cid =Request::instance()->param('cid');
        $_GET['cid'] = $cid;
        $page =Request::instance()->param('page');
        $_GET['page'] = $page ?? 0;
        return $this->fetch();
    }
    /**最近添加的节点
     * @return mixed
     */
    public function topicpage()
    {
        $tid =Request::instance()->param('tid');
        $_GET['tid'] = $tid;
        $page =Request::instance()->param('page');
        $_GET['page'] = $page ?? 0;
        return $this->fetch();
    }
    /**账号修改
     * @return mixed
     */
    public function adminSetuser()
    {
    $mid =Request::instance()->param('mid');
        $_GET['mid'] = $mid;
        return $this->fetch();
    }

    /**网站设置
     * @return mixed
     */
    public function adminSetting()
    {
        return $this->fetch();
    }

    /**注册
     * @return mixed
     */
    public function sigin()
    {
        return $this->fetch();
    }
    /**分类管理
     * @return mixed
     */
    public function adminNode()
    {
        $nid =Request::instance()->param('nid');
        $_GET['nid'] = $nid;

        return $this->fetch();
    }

    /**管理员编辑评论
     * @return mixed
     */
    public function adminEditComment()
    {
        $rid =Request::instance()->param('rid');
        $_GET['rid'] = $rid;
        return $this->fetch();
    }
    /**管理员编辑帖子
     * @return mixed
     */
    public function adminEditPost()
    {
        $tid =Request::instance()->param('tid');
        $_GET['tid'] = $tid;
        return $this->fetch();
    }
    /**连接管理
     * @return mixed
     */
    public function adminLink()
    {
        $act =Request::instance()->param('act');
        $_GET['act'] = $act;
        $lid =Request::instance()->param('lid');
        $_GET['lid'] = $lid;
        return $this->fetch();
    }

    /**连接管理
     * @return mixed
     */
    public function adminUser()
    {
        $act =Request::instance()->param('act');
        $_GET['act'] = $act;
        $lid =Request::instance()->param('lid');
        $_GET['lid'] = $lid;
        return $this->fetch();
    }

    /**RSS订阅
     * @return mixed
     */
    public function feed()
    {
        return $this->fetch();
    }
    /**找回密码
     * @return mixed
     */
    public function forgot()
    {
    $lid =Request::instance()->param('lid');
        $_GET['lid'] = $lid;
        return $this->fetch();
    }

    /**回复信息查询
     * @return mixed
     */
    public function gototopic()
    {
        $tid =Request::instance()->param('tid');
        $_GET['tid'] = $tid;
        return $this->fetch();
    }

    /**回复信息查询
     * @return mixed
     */
    public function indexpage()
    {
        $page =Request::instance()->param('page');
        $_GET['page'] = $page;
        return $this->fetch();
    }

    /**验证码
     * @return mixed
     */
    public function seccode()
    {
        return $this->fetch();
    }

    /** 标签
     * @return mixed
     */
    public function tagpage()
    {
        $tag =Request::instance()->param('tag');
        $_GET['tag'] = $tag;
        $page =Request::instance()->param('page');
        $_GET['page'] = $page;
        return $this->fetch();
    }
    /** 上传文件
     * @return mixed
     */
    public function upload()
    {
        $mw =Request::instance()->param('mw');
        $_GET['mw'] = $mw;
        return $this->fetch();
    }









//    public function __get($name) {
//        print_r(12312312);
//    }
}
