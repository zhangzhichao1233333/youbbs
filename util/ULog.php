<?php
namespace util;
include(CURRENT_DIR . '/vendor/autoload.php');
use think\facade\Log ;

class ULog extends Log{
    public function run()
    {
        Log::init([
            // 日志记录方式，支持 file socket 或者自定义驱动类
            'type'  =>  'File',
            //日志保存目录
            'path'  =>  "../logs/youbbs/",
            //单个日志文件的大小限制，超过后会自动记录到第二个文件
            'file_size'     =>2097152,
            //日志的时间格式，与date函数设置一致，可以自定义时间格式，默认是` c `
            'time_format'   =>'YmdHis c',
            //是否用json格式写入到日志文件
            'json'  =>  false,
        ]);
    }
}
?>
