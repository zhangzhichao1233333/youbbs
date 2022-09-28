<?php
/**
 * @name FavoritesDao.php
 * @author 张志超
 * $date  20122-09-04
 * @desc 爱好
 */

namespace app\index\model\Service;


use app\index\model\Dao\FavoritesDao;


class FavoritesService
{
    protected $name = "Favorites";//指定数据表名

    public function getFavoritesRow(array $where) {
        $favoritesDao = new FavoritesDao();
        return $favoritesDao->getFavoritesRow($where);
    }
}