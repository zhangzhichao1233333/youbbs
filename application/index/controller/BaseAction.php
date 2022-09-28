<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\index\controller;
use app\admin\controller\MainSetting;
use think\Controller;
use think\facade\Env;

#require __DIR__ . '/../thinkphp/start.php';
define('IN_SAESPOT', 1);
define('CURRENT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
define('ROOT_PATH', Env::get('ROOT_PATH'));
require(ROOT_PATH .'library/JGCache.php');
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
