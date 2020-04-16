<?php

require_once('DbModel.php');
require_once 'functions.php';

$return = array();
$return['status'] = "0";

$dbModel = new DbModel();

$accept_options = ['header_html', 'items_number'];

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'setup_type_setting':

            $campaign_id = $_POST['campaign_id'] ?? 0;
            $type = $_POST['type'] ?? 0;
            // options
            $header_html = $_POST['header_html'] ?? '';
            $exists = $dbModel->get_campaign_options_by_type($campaign_id, $type);
            $exist_options = array_column($exists, 'key');

            $count_success = 0;
            foreach ($_POST as $key => $value) {
                // Accept value
                if (in_array($key, $accept_options)) {
                    if (in_array($key, $exist_options)) {
                        // Update
                        $result = $dbModel->update_campaign_type_setting($campaign_id, $type, $key, $value);
                        if ($result) $count_success++;
                    } else {
                        // Insert
                        $result = $dbModel->insert_campaign_type_option($campaign_id, $type, $key, $value);
                        if ($result) $count_success++;
                    }
                }
            }

            if ($count_success > 0) {
                $result = "<label>Cài đặt thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'add_campaign_content':
            $result = $dbModel->insert_campaign_content($_POST);
            if ($result) {
                $result = "<label>Tạo content thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'update_campaign_content':
            $result = $dbModel->update_campaign_content($_POST);

            $keyword_list = $_POST['keyword_list'] ?? '';
            $keywords = explode("\n", str_replace("\r", "", $keyword_list));
            $keywords = array_map('trim', $keywords);

            $keys = [];
            foreach ($keywords as $keyword) {
                $keys[] = md5($keyword);
                $keys[] = 'cmt_' . md5($keyword);
            }
            if ($keys) {
                $redis = new MyRedis();
                $redis->delete($keys);
            }
            if ($result) {
                $result = "<label>Cập nhật content thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'add_campaign':
            $result = $dbModel->insert_campaign($_POST);
            if ($result) {
                $result = "<label>Tạo campaign thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'update_campaign':
            $id = $_POST['id'];
            $result = $dbModel->update_campaign($id, $_POST);
            if ($result) $return['status'] = "1";
            break;
        case 'add_keyword':
            $campaign_id = $_POST['campaign_id'];

            $input_keyword = $_POST['content'];
            $count_success = 0;
            if (!empty($input_keyword) || $input_keyword != '') {
                $keywords = explode("\n", str_replace("\r", "", $input_keyword));
                $keywords = array_map('trim', $keywords);
                foreach ($keywords as $key => $value) {
                    if ($value) {
                        $result = $dbModel->insert_keyword($value, $campaign_id);
                        if ($result) $count_success++;
                    }
                }
            }

            if ($count_success > 0) {
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
            $input_keyword = $_POST['content'];
            $campaign_id = $_POST['campaign_id'];
            $type = $_POST['type'];
            $count_success = 0;
            if (!empty($input_keyword) || $input_keyword != '') {
                $keywords = explode("\n", str_replace("\r", "", $input_keyword));
                $keywords = array_map('trim', $keywords);
                foreach ($keywords as $key => $value) {
                    if ($value) {
                        $result = $dbModel->insert_comment($campaign_id, $value, $type);
                        if ($result) $count_success++;
                    }
                }
            }

            if ($count_success > 0) {
                $result = "<label>Tạo comment thành công.</label>";
                $return['status'] = 1;
                $return['html'] = $result;
            } else {
                $result = "<label>Có lỗi. Vui lòng thử lại.</label>";
                $return['status'] = 0;
                $return['html'] = $result;
            }
            break;
        case 'delete_group':
            $id = $_POST['id'];
            $result = $dbModel->delete_group($id);
            if ($result) {
                $return['status'] = "1";
            }
            break;
        case 'delete_comment':
            $comment_id = $_POST['id'];
            $result = $dbModel->delete_comment($comment_id);
            if ($result) {
                $return['status'] = "1";
            }
            break;
        case 'delete_campaign':
            $id = $_POST['id'];
            $result = $dbModel->delete_campaign($id);
            if ($result) {
                $return['status'] = "1";
            }
            break;
        case 'clone_campaign':
            $id = $_POST['id'];
            $result = $dbModel->clone_campaign($id);
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