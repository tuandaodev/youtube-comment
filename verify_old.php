<?php

require_once 'DbModel.php';
require_once 'youtube_api.php';
require_once 'spinner.php';

$dbModel = new DbModel();

$campaign_id = $_REQUEST['cid'];
$campaign = $dbModel->get_campaign_by_id($campaign_id);
if (!$campaign) {
    echo "Campaign Not Found";
    exit;
}

$keyword = $dbModel->get_keyword_random($campaign_id);
if (!$keyword) {
    echo "Empty Keyword.";
    exit;
}

$video_ids = [];
$video_list = [];
if (isset($keyword['video_list']) && !empty($keyword['video_list'])) {
    $video_list = json_decode($keyword['video_list'], true);
} else {
    $result = get_video($video_ids, $keyword['content']);
    if (isset($result['video_list'])) {
        $dbModel->update_keyword_video_list($keyword['id'], $result['video_list']);
        $video_list = $result['video_list'];
    }
}

$maxItems = $campaign['items_number'] ?? 0;
$verifyTimes = $campaign['verify_number'] ?? 0;

$video_list = array_slice($video_list, 0, $maxItems);
$comments = $dbModel->get_comment_random($campaign_id, $maxItems, 1);

$spintax = new Spintax();

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
    <div class="title"><b>POST THE COMMENTS BELOW TO YOUTUBE</b></div>
    <div class="description"><p>To prevent robot abuse, you are required to complete human verification by posting each
            comment below to the video on it's left.</p></div>
    <?php if (isset($campaign['help_image']) && !empty($campaign['help_image'])): ?>
        <p><img class="img-responsive" src="<?php echo urldecode($campaign['help_image']); ?>"></p>
    <?php endif; ?>

    <?php if (isset($campaign['help_video']) && !empty($campaign['help_video'])): ?>
        <button class="showmehow_toggle" onclick="showMeHow()"><i class="fas fa-info-circle"></i> Show Me How</button>
        <div class="showmehow">
            <?php echo $campaign['help_video']; ?>
        </div>
    <?php endif; ?>
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
        foreach ($video_list as $video):
            ?>
            <tr>
                <td>
                    <a class="youtube_link" data-id="<?php echo $count ?>"
                       href="https://www.youtube.com/watch?v=<?php echo $video['id'] ?>" target="_blank">
                        <img width="100" src="<?php echo $video['thumb'] ?? '' ?>" alt="Click here"></a>
                </td>
                <td id="text<?php echo $count ?>" class="notranslate select_text">
                    <?php
                    if (isset($comments[$count]['content'])) {
                        echo $spintax->process($comments[$count]['content']);
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
    <button class="btn" onclick="verify()"><?php echo $campaign['btn_text'] ?? 'Verify' ?></button>
</div>
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
        $(".youtube_link").on('mousedown', function (e) {
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