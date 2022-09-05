<?php
/**
 * @name CategoriesDao.php
 * @author 张志超
 * $date  20122-09-04
 * @desc 类别
 */

namespace app\index\model\Dao;

use think\Model;

class CategoriesDao extends Model
{
    protected $name = "Categories";//指定数据表名
    public function scopeThinkphp($query)
    {
        $query->where('name','thinkphp')->field('id,name');
    }
}