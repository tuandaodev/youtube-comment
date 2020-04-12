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

    public function get_keyword_random() {
        $query = "SELECT * FROM keyword ORDER BY RAND() LIMIT 1";
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

    public function get_comment_random($limit_number = 4, $type = 1) {

        $query = "SELECT * FROM comment WHERE type = $type ORDER BY RAND() LIMIT $limit_number";

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

    public function insert_comment($content, $type = 1) {
        $query = '  INSERT INTO comment(content, type)
                        VALUES (
                        "' . mysqli_real_escape_string($this->link, $content) . '",
                        "' . $type . '")';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function insert_keyword($content) {
        $query = '  INSERT INTO keyword(content)
                        VALUES (
                        "' . mysqli_real_escape_string($this->link, $content) . '")';
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function get_all_keyword() {
        $query = "SELECT * FROM keyword order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];
    }

    public function get_all_comment() {
        $query = "SELECT * FROM comment order by id DESC";
        $result = mysqli_query($this->link, $query);
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if ($return) return $return;
        }
        return [];

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

