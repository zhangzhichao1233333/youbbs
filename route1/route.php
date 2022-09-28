<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;
Route::get('new/free', function () {
    return 'index.php11';
});
Route::get('free', function () {
    return 'index.php';
});
//首页
Route::rule('index','index/index');
//分类管理
Route::rule('admin-node','index/adminNode');
Route::rule('admin-node/:nid','index/adminNode');

//收藏
Route::rule('favorites','index/favorites');
//设置
Route::rule('setting','index/setting');
//退出
Route::rule('logout','index/logout');
//登录
Route::rule('login','index/login');
//注册
Route::rule('sigin','index/sigin');
//编辑用户信息
//Route::get('user','index/member');
//编辑用户信息
Route::get('user/:mid','index/member');
//创建新主题
Route::rule('newpost/:cid','index/newpost');
//未读短信
Route::rule('notifications','index/notifications');
//最近添加的节点
Route::get('nodes/:cid/:page','index/nodepage');
Route::get('nodes/:cid','index/nodepage');

//帖子详情 rewrite ^/topics/([0-9]+)(/([0-9]*))?$ /topicpage.php?tid=$1&page=$3 last;
Route::get('topics/:tid/:page','index/topicpage');
Route::rule('topics/:tid','index/topicpage');
//setting rewrite ^/(login|sigin|logout|forgot|setting|install)$ /$1.php last;
Route::get('setting','index/setting');
//setting rewrite ^/(login|sigin|logout|forgot|setting|install)$ /$1.php last;
Route::rule('admin-setuser/:mid','index/adminSetuser');

//setting rewrite ^/(login|sigin|logout|forgot|setting|install)$ /$1.php last;
Route::get('admin-edit-comment/:rid','index/adminEditComment');
//管理员编辑帖子
Route::rule('admin_edit_post/:tid','index/adminEditPost');
//连接管理
Route::rule('admin-link/:act/:lid','index/adminLink');
Route::rule('admin-link/:act','index/adminLink');
//网站设置
Route::rule('admin-setting','index/adminSetting');
//用户管理
Route::rule('admin-user/:act/:lid','index/adminUser');
Route::rule('admin-user/:act','index/adminUser');
//RSS订阅
Route::rule('feed', 'index/feed');
//找回密码
Route::rule('forgot', 'index/forgot');

//找回密码
Route::rule('gototopic/:tid', 'index/gototopic');
//回复信息查询 rewrite ^/notic/([0-9]+)$ /gototopic.php?tid=$1 last;
Route::rule('notic/:tid', 'index/gototopic');
//回复信息查询 rewrite ^/notic/([0-9]+)$ /gototopic.php?tid=$1 last;
Route::rule('page/:page', 'index/indexpage');

//验证码 rewrite ^/notic/([0-9]+)$ /gototopic.php?tid=$1 last;
Route::rule('seccode', 'index/seccode');


//标签    rewrite ^/tag/([^\/]+)(/([0-9]+))?$ /tagpage.php?tag=$1&page=$3 last;
Route::rule('tag/:tag/:page', 'index/tagpage');
Route::rule('tag/:tag', 'index/tagpage');

// 上传 rewrite ^/upload-(650|590)$ /upload.php?mw=$1 last;
Route::rule('upload/:mw', 'index/upload');

//err
Route::get('err401','callbackerr/index/err401');
Route::get('err403','callbackerr/index/err403');
Route::get('err404','callbackerr/index/err404');

Route::rule('setting','setting/index');
Route::rule('new/:id','index/News/read');

Route::get('hello/:name', 'index/hello');

return [

];
