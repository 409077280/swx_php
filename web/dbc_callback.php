<?php

// [ 数据中心回调接口 ]

// 请求模块
$m = (isset($_GET['m']) && $_GET['m']) ? $_GET['m'] : 'order';

// 转发路由
if($m == 'order')
    $_GET['s'] = '/task/dbcenter.order/notify';
else if($m == 'user')
    $_GET['s'] = '/task/dbcenter.user/notify';

// 定义运行目录
define('WEB_PATH', __DIR__ . '/');

// 定义应用目录
define('APP_PATH', WEB_PATH . '../source/application/');

// 加载框架引导文件
require APP_PATH . '../thinkphp/start.php';
