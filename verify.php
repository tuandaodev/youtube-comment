<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once 'DbModel.php';
require_once 'vendor/autoload.php';
use RedisClient\RedisClient;

$dbModel = new DbModel();

// Load Data
$campaign_id = @$_REQUEST['cid'];
$campaign = $dbModel->get_campaign_by_id($campaign_id);
if (!$campaign) {
    echo "Campaign Not Found";
    exit;
}
$group = $dbModel->get_group_random($campaign_id);
if (!$group) {
    echo "Empty Data";
    exit;
}

// Load Mapping Settings
$temps = $dbModel->get_campaign_options_by_type($campaign_id, $group['type']);
$settings = [];
foreach ($temps as $item) {
    $settings[$item['key']] = $item['value'];
}

// Load Groups
$groups = [];
if ($group['type'] != 1 && $group['type'] != 2) {
    $maxItems = $settings['items_number'] ?? 1;
    $groups = $dbModel->get_groups_random_max($campaign['id'], $group['type'], $maxItems);
}

// SAME HERE
$result = processData($campaign, $group, $groups, $settings);
$url_list = $result['url_list'] ?? [];
$comment_list = $result['comment_list'] ?? [];
$groups_list = $result['groups_list'] ?? [];

$verifyTimes = $campaign['verify_number'] ?? 0;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify</title>
    <meta name="robots" content="noindex">
    <meta name="referrer" content="none">
    <meta name="description" content="Complete any offer below to unlock this page.">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/styles.css" media="all">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
</head>
<body>
<div class="wrapper">

    <?php if (isset($settings['header_html']) && !empty($settings['header_html'])): ?>
            <?php echo $settings['header_html']; ?>
    <?php else: ?>
        <div class="title"><b>POST THE COMMENTS BELOW TO YOUTUBE</b></div>
        <div class="description"><p>To prevent robot abuse, you are required to complete human verification by posting each comment below to the video on it's left.</p></div>
    <?php endif; ?>

    <?php
        if ($group['type'] == 5 || $group['type'] == 6 || $group['type'] == 7) renderTableCustom($groups_list);
        else if ($group['type'] == 3 || $group['type'] == 4) renderTable34($groups_list);
        else renderTable($group, $url_list, $comment_list);
    ?>

    <button id="btn-verify" class="btn btn-primary btn-verify" onclick="verify()"><?php echo $campaign['btn_text'] ?? 'Verify' ?></button>
</div>

<?php if (isset($campaign['custom_css']) && !empty($campaign['custom_css'])): ?>
<style>
<?php echo $campaign['custom_css'] ?? '' ?>
</style>
<?php endif; ?>

<script>
    var get = function (key) {
        return window.localStorage ? window.localStorage[key] : null;
    }
    var put = function (key, value) {
        if (window.localStorage) {
            window.localStorage[key] = value;
        }
    }
    $(document).ready(function () {
        var clipboard = new ClipboardJS('.btn-copy');
        clipboard.on('success', function(e) {
            var id = e.trigger.getAttribute('data-id');
            $("#text" +id).attr('check-condition', 1);
            $("#btnCopy" +id).html('<i class="fa fa-copy"></i> Copied');
            $("#btnCopy" +id).removeClass('btn-outline-info').addClass('btn-info');
            e.clearSelection();
        });
        $(".select_text").bind('copy', function () {
            $(this).attr('check-condition', 1);
        });
    });

    var ytname = null;
    function verify() {
        var check = true;
        $(".select_text").each(function () {
            if ($(this).attr('check-condition') != 1) {
                alert("Please complete all the tasks to verify.");
                check = false;
                return false;
            }
        });
        if (check) {
            // if (ytname != '' || ytname == null || ytname.length == 0) {
            //     ytname = prompt("Enter your Youtube Name");
            // }
            var verify_count = get('verify_count');
            if (!verify_count) verify_count = 0;
            $("#btn-verify").html('Processing...');
            $("#btn-verify").attr("disabled", true);
            setTimeout(function () {
                verify_count = parseInt(verify_count) + 1;
                if (verify_count >= <?php echo $verifyTimes ?>) {
                    put('verify_count', 0);
                    window.location.href = "<?php echo urldecode($campaign['landing_page']) ?>";
                } else {
                    put('verify_count', verify_count);
                    alert("Verification failed! Your comments coundn't be found.");
                    window.location.reload();
                }
            }, 2000);
        }
    }
</script>

</body>
</html>

<?php

function renderTable($group, $url_list, $comment_list) {
    $spintax = new Spintax();
?>
    <table class="videos">
        <thead>
        <tr>
            <th>Open video</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count = 0;
        foreach ($url_list as $url):
            $comment_text = $spintax->process($comment_list[array_rand($comment_list, 1)]);
            $count++;
            ?>
            <tr>
                <td>
                    <a class="external_link" data-id="<?php echo $count ?>"
                       href="<?php echo $url['url'] ?>" target="_blank">
                        <img width="100" src="<?php echo $url['image'] ?? '' ?>" alt="Click here"></a>
                </td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php echo $comment_text ?>
                    &nbsp;<button id="btnCopy<?php echo $count ?>" class="btn btn-outline-info btn-sm btn-copy" data-id="<?php echo $count ?>" data-clipboard-text="<?php echo $comment_text ?>">
                        <i class="fa fa-copy"></i> Copy
                    </button>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
<?php
}

function renderTable34($groups_list) {
    $spintax = new Spintax();
    ?>
    <table class="videos">
        <thead>
        <tr>
            <th>Open video</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count = 0;
        foreach ($groups_list as $item):
            $comment_list = $item['comment_list'] ?? [];
            $comment_text = $spintax->process($comment_list[array_rand($comment_list, 1)]);
            $count++;
            ?>
            <tr>
                <td>
                    <a class="external_link" data-id="<?php echo $count ?>"
                       href="<?php echo $item['url'] ?>" target="_blank">
                        <img width="100" src="<?php echo $item['image'] ?? '' ?>" alt="Click here"></a>
                </td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php echo $comment_text ?>
                    &nbsp;<button id="btnCopy<?php echo $count ?>" class="btn btn-outline-info btn-sm btn-copy" data-id="<?php echo $count ?>" data-clipboard-text="<?php echo $comment_text ?>">
                        <i class="fa fa-copy"></i> Copy
                    </button>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <?php
}

function renderTableCustom($groups_list) {
    $spintax = new Spintax();
    ?>
    <table class="videos">
        <thead>
        <tr>
            <th>Content</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count = 0;
        foreach ($groups_list as $item):
            $comment_list = $item['comment_list'] ?? [];
            $comment_text = $spintax->process($comment_list[array_rand($comment_list, 1)]);
            $count++;
            ?>
            <tr>
                <td><?php echo $item['custom_html'] ?? '' ?></td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php echo $comment_text ?>
                    &nbsp;<button id="btnCopy<?php echo $count ?>" class="btn btn-outline-info btn-sm btn-copy" data-id="<?php echo $count ?>" data-clipboard-text="<?php echo $comment_text ?>">
                        <i class="fa fa-copy"></i> Copy
                    </button>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <?php
}

function processData($campaign, $group, $groups, $settings) {
    $result = [];
    if ($group['type'] == 1) $result = processDataType1($campaign, $group, $settings);
    if ($group['type'] == 2) $result = processDataType2($campaign, $group, $settings);
    if ($group['type'] == 3) $result = processDataType34($campaign, $groups, $settings);
    if ($group['type'] == 4) $result = processDataType34($campaign, $groups, $settings);
    if ($group['type'] == 5 || $group['type'] == 6 || $group['type'] == 7) $result = processDataTypeCustom($campaign, $groups, $settings);
    return $result;
}

function processDataType1($campaign, $group, $settings) {
    // Process Data
    $keywords = explode("\n", str_replace("\r", "", $group['keyword_list'] ?? []));
    $keywords = array_map('trim', $keywords);
    $keyword = $keywords[array_rand($keywords, 1)];

    $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
    $comment_list = array_map('trim', $comment_list);

    $result = youtube_get_videos($keyword);
    $video_list = $result['video_list'] ?? [];
    $maxItems = $settings['items_number'] ?? 0;
    shuffle($video_list);
    $url_list = array_slice($video_list, 0, $maxItems);

    $result['comment_list'] = $comment_list;
    $result['url_list'] = $url_list;
    return $result;
}

function processDataType2($campaign, $group, $settings) {
    // Process Data
    $keywords = explode("\n", str_replace("\r", "", $group['keyword_list'] ?? []));
    $keywords = array_map('trim', $keywords);
    $keyword = $keywords[array_rand($keywords, 1)];

    $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
    $comment_list = array_map('trim', $comment_list);

    $maxItems = $settings['items_number'] ?? 0;

    $search_key_word = $group['keyword'] ?? '';
    $result = youtube_get_comments($keyword, $search_key_word, $maxItems);
    $url_list = $result['comment_list'] ?? [];

    shuffle($comment_list);
    $comment_list = array_slice($comment_list, 0, $maxItems);

    $result['comment_list'] = $comment_list;
    $result['url_list'] = $url_list;
    return $result;
}

function processDataType34($campaign, $groups, $settings) {
    $groups_list = [];
    foreach ($groups as $group) {
        // Get Video Thumb From URL
        $video_url = urldecode($group['url']);
        $parts = parse_url($video_url);
        if (isset($parts['query']) && !empty($parts['query'])) {
            parse_str($parts['query'], $query);
            $video_id = $query['v'] ?? '';
            $video_image = get_video_image($video_id);
        }
        $_temp['url'] = $video_url;
        $_temp['image'] = $video_image ?? 'https://via.placeholder.com/200x150?text=No%20Image';

        $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
        $comment_list = array_map('trim', $comment_list);
        $_temp['comment_list'] = $comment_list;

        $groups_list[] = $_temp;
    }
    $result['groups_list'] = $groups_list;
    return $result;
}

function processDataTypeCustom($campaign, $groups, $settings) {
    $groups_list = [];
    foreach ($groups as $group) {
        $_temp['custom_html'] = $group['custom_html'] ?? '';

        $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
        $comment_list = array_map('trim', $comment_list);
        $_temp['comment_list'] = $comment_list;

        $groups_list[] = $_temp;
    }
    $result['groups_list'] = $groups_list;
    return $result;
}

function youtube_get_videos($keyword) {
    $redis = new MyRedis();
    $video_list = $redis->get(md5($keyword));
    if ($video_list) {
        $video_list = json_decode($video_list, true);
    }

    if (!$video_list) {
        $url = 'https://www.googleapis.com/youtube/v3/search';
        $data = [
            'part' => 'id,snippet',
            'q' => $keyword,
            'maxResults' => 20,
            'key' => DEVELOPER_KEY
        ];
        $response = api_call($url, $data);
        $searchResponse = json_decode($response, true) ?? [];

        $video_list = [];
        foreach ($searchResponse['items'] as $searchResult) {
            if ($searchResult['id']['kind'] == 'youtube#video') {
                $thumb = '';
                if (isset($searchResult['snippet']['thumbnails']['medium']['url']) && !empty($searchResult['snippet']['thumbnails']['medium']['url'])) {
                    $thumb = $searchResult['snippet']['thumbnails']['medium']['url'];
                }
                $video_list[$searchResult['id']['videoId']] = array(
                    'id' => $searchResult['id']['videoId'],
                    'url' => 'https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'],
                    'image' => $thumb,
                );
            }
        }
        if ($video_list) {
            $redis->set(md5($keyword), json_encode($video_list));
        }
    }

    $return['video_list'] = $video_list;
    return $return;
}


function youtube_get_comments($keyword, $search_key_word, $maxVideos)
{
        $video_list = youtube_get_videos($keyword);
        $video_list = $video_list['video_list'] ?? [];
        $video_list_id = array_column($video_list, 'id');

        $redis = new MyRedis();
        $comment_id_list = $redis->get('cmt_' . md5($keyword));
        if ($comment_id_list) $comment_id_list = json_decode($comment_id_list, true);

        if (!$comment_id_list && !is_array($comment_id_list)) {
            $request_list = [];
            foreach ($video_list_id as $video_id) {
                $url = 'https://www.googleapis.com/youtube/v3/commentThreads';
                $data = [
                    'part' => 'id,snippet',
                    'videoId' => $video_id,
                    'maxResults' => 20,
                    'order' => 'relevance',
                    'key' => DEVELOPER_KEY,
                    'searchTerms' => $search_key_word
                ];
                if (!empty($data) && is_array($data)) {
                    $request_list[] = $url . '?' . http_build_query($data, '', '&');
                }
            }

            $comment_id_list = runRequestsCustomConvertFunc($request_list, $search_key_word);
            $redis->set('cmt_' . md5($keyword), json_encode($comment_id_list));
        }

        $comment_list = [];
        $count_success = 0;
        $exists_video_id = [];
        if (!empty($comment_id_list)) {
            shuffle($comment_id_list);
            foreach ($comment_id_list as $comment) {
                $video_id = $comment['video_id'];
                if (!in_array($video_id, $exists_video_id)) {
                    $exists_video_id[] = $video_id;
                    $_temp['video_id'] = $video_id;
                    $_temp['url'] = "https://www.youtube.com/watch?v={$video_id}&lc={$comment['comment_id']}";
                    $_temp['image'] = $video_list[$video_id]['image'] ?? '';
                    $comment_list[] = $_temp;
                    $count_success++;
                    if ($count_success == $maxVideos) break;
                }
            }
            // Load more data
            if ($count_success < $maxVideos) {
                foreach ($comment_id_list as $comment) {
                    $video_id = $comment['video_id'];
                    $_temp['video_id'] = $video_id;
                    $_temp['url'] = "https://www.youtube.com/watch?v={$video_id}&lc={$comment['comment_id']}";
                    $_temp['image'] = $video_list[$video_id]['image'] ?? '';
                    $comment_list[] = $_temp;
                    $count_success++;
                    if ($count_success == $maxVideos) break;
                }
            }
        }

        $return['comment_list'] = $comment_list;
//        $return['video_list'] = $comment_list;
        return $return;
}

function runRequestsCustomConvertFunc($url_array, $search_key_word) {

    $thread_width = 8;
    $threads = 0;
    $master = curl_multi_init();
    $curl_opts = array(
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => TRUE);

    $all_comment_ids = array();
    $count = 0;
    foreach($url_array as $url) {
        $ch = curl_init();
        $curl_opts[CURLOPT_URL] = $url;
        curl_setopt_array($ch, $curl_opts);
        curl_multi_add_handle($master, $ch); //push URL for single rec send into curl stack
        $threads++;
        $count++;
        if($threads >= $thread_width) { //start running when stack is full to width
            while($threads >= $thread_width) {
                usleep(100);
                while(($execrun = curl_multi_exec($master, $running)) === -1){}
                curl_multi_select($master);
                while($done = curl_multi_info_read($master)) {
                    $api_result = curl_multi_getcontent($done['handle']);
                    $api_result = json_decode($api_result, true);
                    $comment_ids = reduce_comment_response($api_result, $search_key_word);
                    $all_comment_ids = array_merge($all_comment_ids, $comment_ids);
                    curl_multi_remove_handle($master, $done['handle']);
                    curl_close($done['handle']);
                    $threads--;
                }
            }
        }
    }
    do { //finish sending remaining queue items when all have been added to curl
        usleep(100);
        while(($execrun = curl_multi_exec($master, $running)) === -1){}
        curl_multi_select($master);
        while($done = curl_multi_info_read($master)) {
            $api_result = curl_multi_getcontent($done['handle']);
            $api_result = json_decode($api_result, true) ?? [];
            $comment_ids = reduce_comment_response($api_result, $search_key_word);
            $all_comment_ids = array_merge($all_comment_ids, $comment_ids);
            curl_multi_remove_handle($master, $done['handle']);
            curl_close($done['handle']);
            $threads--;
        }
    } while($running > 0);
    curl_multi_close($master);
    return $all_comment_ids;
}

function reduce_comment_response($data_single, $search_key_word) {
    $result = [];
    if (!$data_single) return $result;
    if (isset($data_single['items'])) {
        foreach ($data_single['items'] as $comment) {
            $comment_text = $comment['snippet']['topLevelComment']['snippet']['textOriginal'] ?? '';
            if ($search_key_word) {
                if (strpos($comment_text, $search_key_word) !== false) {
                    $video_id = $comment['snippet']['videoId'];
                    $result[] = array(
                        'video_id' => $video_id,
                        'comment_id' => $comment['id']
                    );
                }
            } else {
                $video_id = $comment['snippet']['videoId'];
                $result[] = array(
                    'video_id' => $video_id,
                    'comment_id' => $comment['id']
                );
            }
        }
    }
    return $result;
}

function get_video_image($video_id)
{
    if (!$video_id) return '';
    return "https://i.ytimg.com/vi/$video_id/mqdefault.jpg";
}

function api_call($url, $data = []) {
    if (!empty($data) && is_array($data)) {
        $url = $url . '?' . http_build_query($data, '', '&');
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

class Spintax
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*?)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}



class MyRedis
{
    private $client = null;

    public function __construct()
    {
        if (!$this->client) {
            $this->client = new RedisClient([
                'server' => '127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
//                'server' => 'unix:///home/streamap/.applicationmanager/redis.sock', // or 'unix:///tmp/redis.sock'
                'timeout' => 2
            ]);
        }
    }

    function __destruct()
    {
        if ($this->client) {
            $this->client->quit();
            $this->client = null;
        }
    }

    public function quit()
    {
        if ($this->client) {
            $this->client->quit();
            $this->client = null;
        }
    }

    //Test function
    public function ping()
    {
        echo $this->client->ping();
    }

    public function set($id, $value)
    {
        return $this->client->set($id, $value, 3600*6);
    }

    public function get($id)
    {
        $value = $this->client->get($id);
        return ($value ? $value : null);
    }
}