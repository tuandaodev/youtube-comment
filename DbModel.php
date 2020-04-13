<?php

require_once('config.php');
/**
 *
 * @author MT
 */
    
class DbModel {

    private $link = null;

    public function __construct() {
        $this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($this->link, "utf8");
    }
    
    public function __destruct() {
        if ($this->link != null) {
            mysqli_close($this->link);
        }
    }
    
    public function close() {
        mysqli_close($this->link);
    }
    
    public function check_login($username, $password) {
        $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
        
        $result = mysqli_query($this->link, $query);
        
        $count = mysqli_num_rows($result);
        
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function query($query) {
        $result = mysqli_query($this->link, $query);
        
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $return = array();
        }
        
        return $return;
    }

    public function get_keyword_by_id($id) {

        $query = "SELECT * FROM keyword WHERE id = '$id' LIMIT 1";

        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }

    public function get_group_random($campaign_id = null) {
        $where = '';
        if ($campaign_id) $where = 'WHERE campaign_id = ' . $campaign_id;
        $query = "SELECT * FROM `groups`  $where  ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }

    public function get_keyword_random($campaign_id = null) {
        $where = '';
        if ($campaign_id) $where = 'WHERE campaign_id = ' . $campaign_id;
        $query = "SELECT * FROM keyword  $where  ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }
    
    public function get_comment_by_id($id) {
		
        $query = "SELECT * FROM comment WHERE id = '$id' LIMIT 1";
		
        $result = mysqli_query($this->link, $query);
		
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }

    public function get_comment_random($campaign_id = null, $limit_number = 4, $type = 1) {

        $where = '';
        if ($campaign_id) $where = ' campaign_id = ' . $campaign_id . ' AND ';

        $query = "SELECT * FROM comment WHERE  $where  type = $type ORDER BY RAND() LIMIT $limit_number";

        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }
    
    public function insert_url($uid, $url, $type = 1) {
        
        $query = '  INSERT INTO url(uid, url, type, created)
                        VALUES (
                        "' . $uid . '",
                        "' . urlencode($url) . '",
                        "' . $type . '",
                        "' . time() . '")';
        
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }

    public function insert_comment($campaign_id, $content, $type = 1) {
        $query = '  INSERT INTO comment(campaign_id, content, type)
                        VALUES (
                        "' . $campaign_id . '",
                        "' . mysqli_real_escape_string($this->link, $content) . '",
                        "' . $type . '")';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function insert_keyword($content, $campaign_id) {
        $query = '  INSERT INTO keyword(content, campaign_id)
                        VALUES (
                        "' . mysqli_real_escape_string($this->link, $content) . '", ' . $campaign_id . ')';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function get_all_keyword($campaign_id = null) {
        $where = '';
        if ($campaign_id) $where = 'WHERE campaign_id = ' . $campaign_id;
        $query = "SELECT * FROM keyword  $where  order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }

    public function get_all_group($campaign_id = null) {
        $where = '';
        if ($campaign_id) $where = 'WHERE campaign_id = ' . $campaign_id;
        $query = "SELECT * FROM groups  $where  order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }

    public function get_all_comment($campaign_id) {
        $query = "SELECT * FROM comment WHERE campaign_id = $campaign_id order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];

    }

    public function delete_group($id) {
        $query = "DELETE FROM `groups` WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function delete_comment($id) {
        $query = "DELETE FROM comment WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }
    
    public function update_comment($id, $content, $type) {
        $content = mysqli_real_escape_string($this->link, $content);
        $query = "  UPDATE comment 
                    SET content = '$content', type = $type
                    WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function get_all_campaign() {
        $query = "SELECT * FROM campaign order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];

    }

    public function get_campaign_by_id($id) {

        $query = "SELECT * FROM campaign WHERE id = '$id' LIMIT 1";

        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }

    public function insert_campaign_content($data) {
        $group_name = mysqli_real_escape_string($this->link, $data['group_name'] ?? '');
        $campaign_id = $data['campaign_id'] ?? 0;
        $type = $data['type'] ?? 0;
        $keyword_list = mysqli_real_escape_string($this->link, $data['keyword_list'] ?? '');
        $comment_list = mysqli_real_escape_string($this->link, $data['comment_list'] ?? '');
        $channel = mysqli_real_escape_string($this->link, $data['channel'] ?? '');
        $url = mysqli_real_escape_string($this->link, $data['url'] ?? '');

        $query = '  INSERT INTO `groups`(group_name, campaign_id, `type`, keyword_list, comment_list, `channel`, url)
                        VALUES (
                        "' . $group_name . '",
                        "' . $campaign_id . '",
                        "' . $type . '",
                        "' . $keyword_list . '",
                        "' . $comment_list . '",
                        "' . $channel . '",
                        "' . urlencode($url) . '"
                        )';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function insert_campaign($data) {
        $name = mysqli_real_escape_string($this->link, $data['name']);
        $verify_number = $data['verify_number'] ?? 0;
        $landing_page = mysqli_real_escape_string($this->link, $data['landing_page']);
        $btn_text = mysqli_real_escape_string($this->link, $data['btn_text']);
        $custom_css = mysqli_real_escape_string($this->link, $data['custom_css'] ?? '');

        $query = '  INSERT INTO campaign(name, verify_number, landing_page, btn_text, custom_css)
                        VALUES (
                        "' . $name . '",
                        "' . $verify_number . '",
                        "' . urlencode($landing_page) . '",
                        "' . $btn_text . '",
                        "' . $custom_css . '"
                        )';

        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function update_campaign($id, $data) {
        $name = mysqli_real_escape_string($this->link, $data['name']);
        $verify_number = $data['verify_number'] ?? 0;
        $landing_page = mysqli_real_escape_string($this->link, $data['landing_page']);
        $btn_text = mysqli_real_escape_string($this->link, $data['btn_text']);
        $custom_css = mysqli_real_escape_string($this->link, $data['custom_css'] ?? '');

        $query = '  UPDATE campaign 
                    SET name = "' . $name . '",
        verify_number = "' . $verify_number . '",
        landing_page = "' . urlencode($landing_page) . '",
        custom_css = "' . $custom_css . '",
        btn_text = "' . $btn_text . '"
        WHERE id = ' . $id;

        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function delete_campaign($id) {
        $query = "DELETE FROM campaign WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        $query = "DELETE FROM comment WHERE campaign_id = $id";
        $result = mysqli_query($this->link, $query);
        $query = "DELETE FROM keyword WHERE campaign_id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function delete_keyword($id) {
        $query = "DELETE FROM keyword WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function update_keyword($id, $content) {
        $content = mysqli_real_escape_string($this->link, $content);
        $query = "  UPDATE keyword 
                    SET content = '$content'
                    WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function update_keyword_video_list($id, $videoList) {
        $expired_at = date('Y-m-d H:i:s', strtotime('+1 day'));
        $videoList = mysqli_real_escape_string($this->link, json_encode($videoList));
        $query = "  UPDATE keyword 
                    SET video_list = '$videoList',
                    expired_at = '$expired_at'
                    WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }


    public function insert_campaign_type_option($campaign_id, $type, $key, $value) {
        $value = mysqli_real_escape_string($this->link, $value);
        $query = '  INSERT INTO `options`(campaign_id, `type`, `key`, `value`)
                        VALUES (
                        "' . $campaign_id . '",
                        "' . $type . '",
                        "' . $key . '",
                        "' . $value . '"
                        )';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function get_campaign_options_all($campaign_id) {
        $query = "SELECT * FROM `options` WHERE campaign_id = '$campaign_id'";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }

    public function get_campaign_options_by_type($campaign_id, $type) {
        $query = "SELECT * FROM `options` WHERE campaign_id = '$campaign_id' AND type = '$type' ";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }

    public function update_campaign_type_setting($campaign_id, $type, $key, $value) {
        $value = mysqli_real_escape_string($this->link, $value);
        $query = "  UPDATE `options`
                    SET options.value = '$value'
                    WHERE options.campaign_id = '$campaign_id' AND options.type = '$type' AND `options`.`key` = '$key'";
        return mysqli_query($this->link, $query);
    }

    public function update_option($key, $value) {
        $query = "  UPDATE options 
                    SET options.value = '$value'
                    WHERE options.key = '$key'";
        return mysqli_query($this->link, $query);
    }

    public function get_option($key) {
        $query = "SELECT * FROM options WHERE key = '$key' LIMIT 1";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            if ($return) return $return;
        }
        return [];
    }

    public function get_options() {
        $query = "SELECT * FROM options";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $mapping = [];
            foreach ($return as $item) {
                $mapping[$item['key']] = $item['value'] ?? '';
            }
            if ($mapping) return $mapping;
        }
        return [];
    }

    public function update_cache_status($id, $real_size = 0) {
        $query = "  UPDATE cache 
                    SET status = 1, real_size = $real_size
                    WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function delete_cache($id) {
        $query = "DELETE FROM cache WHERE id = $id";
        $result = mysqli_query($this->link, $query);
        return $result;
    }
    // End Cache
}

