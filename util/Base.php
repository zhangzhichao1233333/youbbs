<?php
namespace util;
use think\Container;

include(CURRENT_DIR . '/vendor/autoload.php');
//include(CURRENT_DIR . '/util/ULog.php');
// 加载基础文件
require CURRENT_DIR . '/vendor/topthink/framework/base.php';
// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
echo "<pre>";print_r(Container::get('app'));die;
Container::get('app')->run()->send();
//class Base {
//
//// 支持事先使用静态方法设置Request对象和Config对象
//
//// 执行应用并响应
////Container::get('app')->run()->send();
////    public function run() {
////        (new  \util\ULog())->run();
////    }
//}
//    (new Base())->run();
?>
