<?php
return array(
    'run'=>false,
    'can_run'=>'./runtime/crontab/run_pid',                     // 运行后创建的文件，删除文件后，自动停止
    'interval'=>2,                                              // 运行时间间隔
    'timeout'=>60,                                              // 必须大于interval， 超过秒没有写入最后运行时间，视为已停止，计算方法大概为每次循环总时间加间隔
    'run_file'=>'./runtime/crontab/running.php',                // 最后运行时间
    'run_log'=>'./runtime/crontab/log',                         // 运行的log
    // 循环体,防止代码错误,采用远程请求地址
    'cron_list'=>array(
        // 'http://all.app/crontab/test.php',
    ),
);