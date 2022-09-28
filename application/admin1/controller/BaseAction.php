<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\admin\controller;

use app\index\model\Dao\SettingsDao;
use app\index\model\Dao\UsersDao;
use think\Controller;
use app\index\model\Service\BusinessService;
use think\facade\Env;

#require __DIR__ . '/../thinkphp/start.php';
if(!defined('IN_SAESPOT')) define('IN_SAESPOT', 1);;


if(!defined("CURRENT_DIR")) define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
if(!defined("ROOT_PATH"))   define('ROOT_PATH', Env::get('ROOT_PATH'));
require(ROOT_PATH.'library/JGCache.php');
require(ROOT_PATH .'library/Unit.php');
class BaseAction extends MainSetting
{
    public $curUid;
    public $curUser= null;
    public $curUname;
    public $curUcode;
    public $JGCache;
    public $options;
    public $urlPath;
    public $tpl;
    public function __construct()
    {
        parent::__construct();
    }
}
