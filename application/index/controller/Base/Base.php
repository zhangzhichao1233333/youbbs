<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\index\controller\Base;
use app\admin\controller\MainSetting;
use app\index\model\Dao\CategoriesDao;
use app\index\model\Dao\LinksDao;
use app\index\model\Dao\SettingsDao;
use app\index\model\Dao\UsersDao;
use app\index\model\Service\BusinessService;
use think\Controller;
use think\facade\Env;

#require __DIR__ . '/../thinkphp/start.php';
define('IN_SAESPOT', 1);
define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
define('ROOT_PATH', Env::get('ROOT_PATH'));
include(__DIR__ . '/../../../../conf/mysql.php');
include( __DIR__ . '/../../util/common.php');
require(__DIR__ . '/../../util/include/GoogleAuth/GoogleAuth.php');

class Base extends Controller
{

    public function initialize() {
        global $cache, $options, $DBS, $tpl, $cur_user, $url_path, $starttime, $cur_uid, $formhash, $is_spider,  $cur_uname, $cur_ucode;;

        $this->assign('cache', $cache);
        $this->assign('options', $options);
        $this->assign('DBS', $DBS);
        $this->assign('tpl', $tpl);
        $this->assign('current_dir', CURRENT_DIR);
        $this->assign('cur_user', $cur_user);
        $this->assign('url_path', $url_path);
        $this->assign('starttime', $starttime);
        $this->assign('cur_uid', $cur_uid);
        $this->assign('timestamp', time());
        $this->assign('formhash', $formhash);
        $this->assign('is_spider', $is_spider);
        $this->assign('cur_uname', $cur_uname);
        $this->assign('cur_ucode', $cur_ucode);
    }
}
