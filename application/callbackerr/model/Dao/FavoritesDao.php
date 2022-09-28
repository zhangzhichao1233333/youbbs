<?php
/**
 * @name FavoritesDao.php
 * @author 张志超
 * $date  20122-09-04
 * @desc 爱好
 */

namespace app\index\model\Dao;

use think\Model;

class FavoritesDao extends Model
{
    protected $name = "Favorites";//指定数据表名

    public function getFavoritesRow($where) {
        return  $this->db()->where($where)->find();
    }
}