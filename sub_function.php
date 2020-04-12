<?php

function write_logs($file_name = '', $text = '', $folder_path = 'logs') {

    if (empty($file_name)) {
        $t = date('Ymd');
        $file_name = "logs-{$t}.txt";
    }

//    $folder_path = 'logs';
    $file_path = $folder_path . '/' . $file_name;

    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0755, true);
    }

    $file = fopen($file_path, "a");

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = date('Y-m-d H:i:s', time());

    $body = "\n" . $date . ' ';
    $body .= $text;

    fwrite($file, $body);
    fclose($file);
}
