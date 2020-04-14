<?php

function export_data($data) {
    try {
        $file_name = 'export.json';
        $folder_path = __DIR__;
        $file_path = $folder_path . '/' . $file_name;
//        if (!file_exists($folder_path)) {
//            mkdir($folder_path, 0755, true);
//        }
        $file = fopen($file_path, "w");
        fwrite($file, json_encode($data));
        fclose($file);
        return true;
    } catch (Exception $ex) {
    }
}