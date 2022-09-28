<?php


namespace app\index\model\Dao\Business;


use think\Db;
use think\Model;

class BusinessDao extends Model
{

    public function getArticle() {
        $querySql = 'SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.addtime,a.edittime,a.comments,a.isred,a.top,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
        FROM yunbbs_articles a  
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users u ON a.uid=u.id
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE `cid` > "1"
        ORDER BY `top` DESC ,`edittime` DESC';
        return Db::query($querySql);
    }
    public function getArticleInfo($where) {
        $querySql = "SELECT a.id,a.uid,a.cid,a.ruid,a.title,a.addtime,a.edittime,a.comments,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
        FROM yunbbs_articles a 
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users u ON a.uid=u.id
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE" . $where;
        return Db::query($querySql);
    }

    public function getTableStatus() {
        $querySql = "SHOW TABLE STATUS LIKE 'yunbbs_categories'";
        return Db::query($querySql);
    }

    public function getUsersStatus() {
        $querySql = "SHOW TABLE STATUS LIKE 'yunbbs_users'";
        return Db::query($querySql);
    }

    public function getCategoriesStatus() {
        $querySql = "SHOW TABLE STATUS LIKE 'yunbbs_categories'";
        return Db::query($querySql);
    }

    public function getArticlesStatus() {
        $querySql = "SHOW TABLE STATUS LIKE 'yunbbs_articles'";
        return Db::query($querySql);
    }

    public function getCommentsStatus() {
        $querySql = "SHOW TABLE STATUS LIKE 'yunbbs_comments'";
        return Db::query($querySql);
    }

}