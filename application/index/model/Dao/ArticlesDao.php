<?php
/**
 * @name ArticlesDao.php
 * @author 张志超
 * $date  20122-09-04
 * @desc 文章管理
 */

namespace app\index\model\Dao;
use think\Model;

class ArticlesDao extends Model
{
    protected $name = "Articles";//指定数据表名
    public function scopeThinkphp($query)
    {
        $query->where('name','thinkphp')->field('id,name');
    }
    /*
$query_sql = "SELECT a.id,a.cid,a.uid,a.ruid,a.title,a.addtime,a.edittime,a.comments,a.isred,a.top,c.name as cname,u.avatar as uavatar,u.name as author,ru.name as rauthor
        FROM yunbbs_articles a
        LEFT JOIN yunbbs_categories c ON c.id=a.cid
        LEFT JOIN yunbbs_users u ON a.uid=u.id
        LEFT JOIN yunbbs_users ru ON a.ruid=ru.id
        WHERE `cid` > '1'
        ORDER BY `top` DESC ,`edittime` DESC LIMIT ".$options['home_shownum'];
    */
}