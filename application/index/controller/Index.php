<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\index\controller;

use app\index\model\Service\BusinessService;

class Index extends BaseAction
{

    public function index()
    {
        // 获取最近文章列表 $articledb
        $articledb = $this->JGCache->get('home_articledb');
        if($articledb === FALSE) {
            new MainSetting();
            $businessService = new BusinessService();
            $articles    = $businessService->getArticle();
            // set to cache
            $this->JGCache->set('home_articledb', $articles);
        }
        $this->assign('articles', $articles);
        $this->assign('articledb', $articles);
        $this->assign('articlesNum', count($articles));
        return $this->fetch();
    }
}
