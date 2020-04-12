<?php

require_once 'DbModel.php';
require_once 'youtube_api.php';

$dbModel = new DbModel();

$keyword = $dbModel->get_keyword_random();
if (!$keyword) {
    echo "Empty Keyword.";
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

$video_list = array_slice($video_list, 0, MAX_ITEMS);
$comments = $dbModel->get_comment_random(MAX_ITEMS, 1);
$options = $dbModel->get_options();

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
    <?php if (isset($options['help_1_image']) && !empty($options['help_1_image'])): ?>
    <p><img style="width:100%" src="<?php echo $options['help_1_image']; ?>"></p>
    <?php endif; ?>

    <?php if (isset($options['help_1_video']) && !empty($options['help_1_video'])): ?>
    <button class="showmehow_toggle" onclick="showMeHow()"><i class="fas fa-info-circle"></i> Show Me How</button>
    <div class="showmehow">
        <?php echo $options['help_1_video']; ?>
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
                     <a href="https://www.youtube.com/watch?v=<?php echo $video['id'] ?>" target="_blank">
                         <img width="100" src="<?php echo $video['thumb'] ?? '' ?>" alt="Click here"></a>
                 </td>
                 <td class="notranslate select_text"><?php echo $comments[$count++]['content'] ?? ''; ?>
                 </td>
             </tr>
         <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <button class="btn" onclick="verify()">Verify</button>
</div>
<script>
    var ytname = null;
    function verify() {
        console.log(ytname);
        if (ytname != '' || ytname == null || ytname.length == 0) {
            ytname = prompt("Enter your Youtube name");
            console.log('verify');
        }
        if (ytname != '' && ytname != null)
            setTimeout(function () {
                alert("Verification failed. The comments must be exactly the same.");
                window.location.reload();
            }, 2000);
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