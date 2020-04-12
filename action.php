<?php

require_once('DbModel.php');

$return = array();
$return['status'] = "0";

$dbModel = new DbModel();

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_keyword':
            $content = $_POST['content'];
            $result = $dbModel->insert_keyword($content);
            if ($result) {
                $result = "<label>Tạo keyword thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'add_comment':
            $content = $_POST['content'];
            $type = $_POST['type'];
            $result = $dbModel->insert_comment($content, $type);
            if ($result) {
                $result = "<label>Tạo comment thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'general_link':
            $orginal_url = $_POST['url'];
            $type = $_POST['type'];
            $uid = uniqid();

            $result = $dbModel->insert_url($uid, $orginal_url, $type);

            if ($result) {
                $output_url = DOMAIN . "download.php?id=$uid";
                $result = "<label>Kết quả:</label><input class='form-control' value='$output_url'>";
                $return['status'] = "1";
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi trong quá trình generate link. Vui lòng thử lại.</label>";
                $return['status'] = "1";
                $return['html'] = $result;
            }
            break;
        case 'delete_comment':
            $comment_id = $_POST['id'];
            $result = $dbModel->delete_comment($comment_id);
            if ($result) {
                $return['status'] = "1";
            }
            break;
        case 'update_comment':
            $comment_id = $_POST['id'];
            $content = $_POST['content'];
            $type = $_POST['type'];
            $result = $dbModel->update_comment($comment_id, $content, $type);
            if ($result) {
                $return['status'] = "1";
                // clear cache
//                $content = $dbModel->get_comment_by_id($comment_id);
//                $cache = $dbModel->get_cache_without_status($content['uid']);
//                $path = DOWNLOAD_FOLDER . '/' . $cache['name'];
//                if (file_exists($path)) {
//                    unlink($path);
//                }
//                $dbModel->delete_cache($cache['id']);
            }
            break;
        case 'delete_keyword':
            $id = $_POST['id'];
            $result = $dbModel->delete_keyword($id);
            if ($result) {
                $return['status'] = "1";
            }
            break;
        case 'update_keyword':
            $id = $_POST['id'];
            $content = $_POST['content'];
            $result = $dbModel->update_keyword($id, $content);
            if ($result) {
                $return['status'] = "1";
                // clear cache
//                $content = $dbModel->get_comment_by_id($comment_id);
//                $cache = $dbModel->get_cache_without_status($content['uid']);
//                $path = DOWNLOAD_FOLDER . '/' . $cache['name'];
//                if (file_exists($path)) {
//                    unlink($path);
//                }
//                $dbModel->delete_cache($cache['id']);
            }
            break;
    }
}

echo json_encode($return);