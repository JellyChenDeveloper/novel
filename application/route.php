<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('/top/allvisit_<curpage>/$', 'index', [], ['curpage' => '\d+']);
Route::get('/<cata>_<bookno>/$', 'chapters', [], ['cata' => '\d+', 'bookno' => '\d+']);
Route::get('/<cata>_<bookno>/:chapno', 'content', ['ext'=>'html'], ['cata' => '\d+', 'bookno' => '\d+','chapno'=>'\d+']);

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
