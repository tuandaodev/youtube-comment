<?php

ini_set('memory_limit', '1024M');
set_time_limit(600);
date_default_timezone_set("Asia/Ho_Chi_Minh");

define('DEVELOPER_KEY', 'AIzaSyBQNDAGcbVFBSdFNzbUP1SOgn-mkA7aN-U');

// Database
define('DB_NAME', 'youtube_comment');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

//define('DB_NAME', 'streamap_youtube');
//define('DB_USER', 'streamap_youtube');
//define('DB_PASSWORD', 'B6Bn!~qclBgb');
//define('DB_HOST', 'localhost');
//define('DB_CHARSET', 'utf8mb4');
//define('DB_COLLATE', '');

// User Config
define('VERIFY_TIME', 3);
define('MAX_ITEMS', 2);

// Dev Only
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);