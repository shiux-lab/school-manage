<?php
define('DRIVE', 'sqlite'); // 数据库驱动
define('TABLE', 'student'); // 数据表
define('ADMIN_TABLE', 'admin'); // 用户表
define('DB_CHARSET', 'utf8'); // 字符集

/* MYSQL */
define('HOST', 'localhost'); // 主机
//define('HOST', '10.211.55.3'); // 主机
define('PORT', '3306'); // 端口
define('USER', 'root'); // 用户名
define('PASS', 'root'); // 密码
define('DBNAME', 'school'); // 数据库


/* SQLITE */
define('FILE', __DIR__ . '/../' . 'school.db'); // sqlite文件位置

/* MongoDB */
define('MONGO_HOST', 'localhost'); // 主机
define('MONGO_PORT', '27017'); // 端口
define('MONGO_USER', 'root'); // 用户名
define('MONGO_PASS', 'root'); // 密码
define('MONGO_DBNAME', 'school'); // 数据库