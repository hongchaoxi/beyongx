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

//域名绑定到前端
//Route::domain('www', 'home');
// 子域名到admin模块
//Route::domain('admin', 'admin');

return [
    '__pattern__' => [
        'id' => '\d+',
        'uid' => '\d+',
        'aid' => '\d+',
        'cid' => '\d+',
        'cname' => '\w+'
    ],

];