crontab 2.3
===============

用来在没有权限执行服务器的crontab时，模拟定时任务的。。


安装

composer require selden1992/crontab

第一次刷新页面会生成配置文件
./config/cron_config.php

按照注释修改

===============

实现原理


composer 自动加载 main.php文件

new \crontab\src\run();

main.php 只实例化一个run对象


在服务器垃圾回收时，会执行run对象的__destruct
