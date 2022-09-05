<?php


namespace app\index\model\Service;


use app\index\model\Dao\Business\BusinessDao;

class BusinessService
{
    public function getArticle() {
        $business = new BusinessDao();
        return $business->getArticle();
    }

    public function getTableStatus() {
        $business = new BusinessDao();
        return $business->getTableStatus();
    }

    public function getUsersStatus() {
        $business = new BusinessDao();
        return $business->getUsersStatus();
    }

    public function getCategoriesStatus() {
        $business = new BusinessDao();
        return $business->getCategoriesStatus();
    }

    public function getArticlesStatus() {
        $business = new BusinessDao();
        return $business->getArticlesStatus();
    }

    public function getCommentsStatus() {
        $business = new BusinessDao();
        return $business->getCommentsStatus();
    }


}