<?php

require_once 'DbModel.php';
require_once 'youtube_api.php';
require_once 'spinner.php';

$dbModel = new DbModel();

// Load Data
$campaign_id = $_REQUEST['cid'];
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
$result = processData($campaign, $group, $settings);

$url_list = $result['url_list'] ?? [];
$comment_list = $result['comment_list'] ?? [];

if (count($url_list) <> count($comment_list)) {
    $maxItems = min(count($url_list), count($comment_list));
    $comment_list = array_slice($comment_list, 0, $maxItems);
    $url_list = array_slice($url_list, 0, $maxItems);
}

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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/styles.css" media="all">
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
        if ($group['type'] == 5) renderTableCustom($group, $url_list, $comment_list);
        else renderTable($group, $url_list, $comment_list);
    ?>

    <button class="btn btn-verify" onclick="verify()"><?php echo $campaign['btn_text'] ?? 'Verify' ?></button>
</div>

<?php if ($campaign['custom_css']): ?>
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
        $(".select_text").bind('copy', function () {
            $(this).attr('check-condition', 1);
        });
        $(".external_link").on('mousedown', function (e) {
            $("#text" + $(this).attr('data-id')).attr('check-youtube', 1);
        });
    });

    var ytname = null;
    function verify() {
        var check = true;
        $(".select_text").each(function () {
            if ($(this).attr('check-condition') != 1 || $(this).attr('check-youtube') != 1) {
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
            if (!verify_count) verify_count = 0;
            if (ytname != '' && ytname != null && ytname.length == 0)
                setTimeout(function () {
                    verify_count = parseInt(verify_count) + 1;
                    if (verify_count >= <?php echo $verifyTimes ?>) {
                        put('verify_count', 0);
                        window.location.href = "<?php echo urldecode($campaign['landing_page']) ?>";
                    } else {
                        put('verify_count', verify_count);
                        alert("Verification failed. The comments must be exactly the same.");
                        window.location.reload();
                    }
                }, 2000);
        }
    }

    var showHow = false;

    function showMeHow() {
        if (showHow) {
            showHow = false;
            $('.showmehow').slideUp();
        } else {
            showHow = true;
            $('.showmehow').slideDown();
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
            ?>
            <tr>
                <td>
                    <a class="external_link" data-id="<?php echo $count ?>"
                       href="<?php echo $url['url'] ?>" target="_blank">
                        <img width="100" src="<?php echo $url['image'] ?? '' ?>" alt="Click here"></a>
                </td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php
                    if (isset($comment_list[$count])) {
                        echo $spintax->process($comment_list[$count]);
                    }
                    $count++;
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
<?php
}

function renderTableCustom($group, $url_list, $comment_list) {
    $spintax = new Spintax();
    ?>
    <table class="videos">
        <thead>
        <tr>
            <th>Keyword</th>
            <th>Channel/Website</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count = 0;
        foreach ($url_list as $url):
            ?>
            <tr>
                <td><?php echo $group['keyword_list'] ?? '' ?></td>
                <td><?php echo $group['channel'] ?? '' ?></td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php
                    if (isset($comment_list[$count])) {
                        echo $spintax->process($comment_list[$count]);
                    }
                    $count++;
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <?php
}

function processData($campaign, $group, $settings) {
    $result = [];
    if ($group['type'] == 1) $result = processDataType1($campaign, $group, $settings);
    if ($group['type'] == 2) $result = processDataType2($campaign, $group, $settings);
    if ($group['type'] == 3) $result = processDataType34($campaign, $group, $settings);
    if ($group['type'] == 4) $result = processDataType34($campaign, $group, $settings);
    if ($group['type'] == 5) $result = processDataTypeCustom($campaign, $group, $settings);
    return $result;
}

function processDataType1($campaign, $group, $settings) {
    // Process Data
    $keywords = explode("\n", str_replace("\r", "", $group['keyword_list'] ?? []));
    $keywords = array_map('trim', $keywords);
    $keyword = $keywords[array_rand($keywords, 1)];

    $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
    $comment_list = array_map('trim', $comment_list);

    $result = get_video($keyword);
    $video_list = $result['video_list'] ?? [];
    $maxItems = $settings['items_number'] ?? 0;
    shuffle($video_list);
    $url_list = array_slice($video_list, 0, $maxItems);
    shuffle($comment_list);
    $comment_list = array_slice($comment_list, 0, $maxItems);

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

    $result = get_comment($keyword, $maxItems);
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

function processDataType34($campaign, $group, $settings) {
    // Process Data

    // Get Video Thumb From URL
    $video_url = urldecode($group['url']);
    $parts = parse_url($video_url);
    if ($parts['query'] ?? false) {
        parse_str($parts['query'], $query);
        $video_id = $query['v'] ?? '';
        $video_image = get_video_image($video_id);
    }

    $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
    $comment_list = array_map('trim', $comment_list);

    $maxItems = $settings['items_number'] ?? 1;

    $url_list = [];
    $count = 0;
    while ($count < $maxItems) {
        $_temp['url'] = $video_url;
        $_temp['image'] = $video_image ?? 'https://via.placeholder.com/200x150?text=No%20Image';
        $url_list[] = $_temp;
        $count++;
    }

    shuffle($comment_list);
    $comment_list = array_slice($comment_list, 0, $maxItems);

    $result['comment_list'] = $comment_list;
    $result['url_list'] = $url_list;
    return $result;
}

function processDataTypeCustom($campaign, $group, $settings) {
    // Process Data

    // Get Video Thumb From URL
    $video_url = urldecode($group['url']);
    $parts = parse_url($video_url);
    if ($parts['query'] ?? false) {
        parse_str($parts['query'], $query);
        $video_id = $query['v'] ?? '';
        $video_image = get_video_image($video_id);
    }

    $comment_list = explode("\n", str_replace("\r", "", $group['comment_list'] ?? []));
    $comment_list = array_map('trim', $comment_list);

    $maxItems = $settings['items_number'] ?? 1;

    $url_list = [];
    $count = 0;
    while ($count < $maxItems) {
        $_temp['url'] = $video_url;
        $_temp['image'] = $video_image ?? 'https://via.placeholder.com/200x150?text=No%20Image';
        $url_list[] = $_temp;
        $count++;
    }

    shuffle($comment_list);
    $comment_list = array_slice($comment_list, 0, $maxItems);

    $result['comment_list'] = $comment_list;
    $result['url_list'] = $url_list;
    return $result;
}

?>