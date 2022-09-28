<?php
/**
 * @name Index.php
 * @author 张志超
 * $date  20122-09-04
 * @desc BBS主页
 */
namespace app\callbackerr\controller;

use think\Controller;

class Index extends Controller
{
    /**401
     * @return mixed
     */
    public function err401()
    {
        return $this->fetch();
    }

    /**403
     * @return mixed
     */
    public function err403()
    {
        return $this->fetch();
    }

    /**404
     * @return mixed
     */
    public function err404()
    {
        return $this->fetch();
    }
}
