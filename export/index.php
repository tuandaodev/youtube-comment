<?php

if (!defined('DEVELOPER_KEY')) define('DEVELOPER_KEY', 'AIzaSyBQNDAGcbVFBSdFNzbUP1SOgn-mkA7aN-U');

date_default_timezone_set('Asia/Ho_Chi_Minh');

$all_data = @file_get_contents('data.json');
$all_data = json_decode($all_data, true);

// Load Data
$campaign = $all_data['campaign'] ?? [];
if (!$campaign) {
    echo "Campaign Not Found";
    exit;
}
$all_groups = $all_data['groups'] ?? [];
$group = $all_groups[array_rand($all_groups)];
if (!$group) {
    echo "Empty Data";
    exit;
}

$all_settings = $all_data['settings'] ?? [];
// Load Mapping Settings
$temps = array_filter($all_settings, function ($item) use ($group) {
    return $item['type'] == $group['type'];
});
$settings = [];
foreach ($temps as $item) {
    $settings[$item['key']] = $item['value'];
}

// Load Groups
$groups = [];
if ($group['type'] != 1 && $group['type'] != 2) {
    $maxItems = $settings['items_number'] ?? 1;
    $groups = array_filter($all_groups, function ($item) use ($group) {
        return $item['type'] == $group['type'];
    });
    shuffle($groups);
    $groups = array_slice($groups, 0, $maxItems);
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
                    alert("Please comment on all videos to verify.");
                    check = false;
                    return false;
                }
            });
            if (check) {
                if (ytname != '' || ytname == null || ytname.length == 0) {
                    ytname = prompt("Enter your Youtube Name");
                }
                var verify_count = get('verify_count');
                console.log(verify_count);
                if (!verify_count) verify_count = 0;
                if (ytname != '' && ytname != null || ytname.length == 0) {
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
        }
    </script>
    <style>
        *{box-sizing:border-box}html{text-align:center}body{background:none transparent;padding:10px;margin:0;display:inline-block}body::-webkit-scrollbar{width:.5em}body::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 .5em rgba(0,0,0,.3)}body::-webkit-scrollbar-thumb{background-color:darkgrey;outline:1px solid slategrey}.clr{clear:both}.wrapper{margin:auto;max-width:100%;text-align:center}.showmehow_toggle{margin:10px auto;cursor:pointer;padding:5px;color:#000}.showmehow_toggle>*{color:#000}.showmehow{display:none}.showmehow video{width:100%}.videos{margin:20px 0;border-collapse:collapse;max-width:100%}.videos td{border:solid 1px #61798e;padding:5px}.videos tr td:first-child{min-width:100px}.videos tr td:last-child{width:100%}@media (min-width: 767px){.img-responsive{width:70%}}@media (max-width: 768px){.img-responsive{width:100%}}
    </style>
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

    $result = youtube_get_comments($keyword, $maxItems);
    $video_list = $result['comment_list'] ?? [];
    shuffle($video_list);
    $url_list = [];
    $video_exists = [];
    $count = 0;
    foreach ($video_list as $video) {
        if ($count >= $maxItems) break;
        if (!in_array($video['video_id'], $video_exists)) {
            $url_list[] = $video;
            $video_exists[] = $video['video_id'];
            $count++;
        }
    }

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

    $response = load_cache_keyword($keyword);
    if (!$response) {
        $url = 'https://www.googleapis.com/youtube/v3/search';
        $data = [
            'part' => 'id,snippet',
            'q' => $keyword,
            'maxResults' => 50,
            'key' => DEVELOPER_KEY
        ];
        $response = api_call($url, $data);
        if ($response) {
            cache_keyword($keyword, $response);
        }
    }
    $searchResponse = json_decode($response, true) ?? [];

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
            $list_id[] = $searchResult['id']['videoId'];
        }
    }
    $return['video_list'] = $video_list;
    return $return;
}


function youtube_get_comments($keyword, $maxVideos, $maxResults = 50)
{
    $video_list = youtube_get_videos($keyword);
    $video_list = $video_list['video_list'] ?? [];
    $list_id = array_column($video_list, 'id');
    shuffle($list_id);
    $list_id = array_slice($list_id, 0, $maxVideos + 1);

    $comment_list = [];

    $count_success = 0;
    foreach ($list_id as $video_id) {
        $temp = youtube_get_comment($keyword, $video_id);
        if (!empty($temp)) {
            $count_success++;
            foreach ($temp as $comment_id) {
                $_temp['video_id'] = $video_id;
                $_temp['url'] = "https://www.youtube.com/watch?v=$video_id&lc=$comment_id";
                $_temp['image'] = $video_list[$video_id]['image'] ?? '';
                $comment_list[] = $_temp;
            }
        }
        if ($count_success == $maxVideos) break;
    }
    $return['comment_list'] = $comment_list;
//        $return['video_list'] = $comment_list;
    return $return;
}

function get_video_image($video_id)
{
    if (!$video_id) return '';
    return "https://i.ytimg.com/vi/$video_id/mqdefault.jpg";
    /*
    $response = load_cache_video($video_id);
    if (!$response) {
        $url = 'https://www.googleapis.com/youtube/v3/videos';
        $data = [
            'part' => 'snippet',
            'id' => $video_id,
            'key' => DEVELOPER_KEY
        ];
        $response = api_call($url, $data);
        if ($response) {
            cache_video($video_id, $response);
        }
    }
    $response = json_decode($response, true) ?? [];

    $videoArr = [];
    if (isset($response['items']) && !empty($response['items'])) {
        $videoArr = $response['items'][0];
    }
    $thumb = '';
    if (isset($videoArr['snippet']['thumbnails']['medium']['url']) && !empty($videoArr['snippet']['thumbnails']['medium']['url'])) {
        $thumb = $videoArr['snippet']['thumbnails']['medium']['url'];
    }
    return $thumb;
    */
}

function youtube_get_comment($keyword, $video_id) {

//    $result = load_cache_comment($keyword, $video_id);
//    if ($result) return $result;

    $url = 'https://www.googleapis.com/youtube/v3/commentThreads';
    $data = [
        'part' => 'id,snippet',
        'videoId' => $video_id,
        'maxResults' => 20,
        'order' => 'relevance',
        'key' => DEVELOPER_KEY
    ];
    $response = api_call($url, $data);
    $data_single = json_decode($response, true) ?? [];

    $result = [];
    if (isset($data_single['items'])) {
        foreach ($data_single['items'] as $comment) {
//            $date = strtotime($comment['snippet']['topLevelComment']['snippet']['publishedAt']);
//            echo date("Y-m-d H:i:s", $date);
            $result[] = $comment['id'];
        }
    }
//    if ($result) {
//        cache_comment($keyword, $video_id, $result);
//    }
    return $result;
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

function load_cache_keyword($keyword) {
    $file_name = md5($keyword);
    $file_path = __DIR__ . '/data/keywords/' . $file_name;
    $result = @file_get_contents($file_path);
    if ($result) {
        if (filectime($file_path) + 3600 < time()) {
            @unlink($file_path);
        }
    }
    return $result;
}

function cache_keyword($keyword, $response) {
    try {
        $file_name = md5($keyword);
        $folder_path = __DIR__ . '/data/keywords';
        $file_path = $folder_path . '/' . $file_name;
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }
        $file = fopen($file_path, "w");
        $body = $response;
        fwrite($file, $body);
        fclose($file);
    } catch (Exception $ex) {
    }
}

function load_cache_video($video_id) {
    $file_name = md5($video_id);
    $file_path = __DIR__ . '/data/videos/' . $file_name;
    $result = @file_get_contents($file_path);
    if ($result) {
        if (filectime($file_path) + 3600 < time()) {
            @unlink($file_path);
        }
    }
    return $result;
}

function cache_video($video_id, $response) {
    try {
        $file_name = md5($video_id);
        $folder_path = __DIR__ . '/data/videos';
        $file_path = $folder_path . '/' . $file_name;
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }
        $file = fopen($file_path, "w");
        $body = $response;
        fwrite($file, $body);
        fclose($file);
    } catch (Exception $ex) {
    }
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