<?php

require_once 'config.php';
require_once 'google-api/vendor/autoload.php';

function get_video($keyword, $maxResults = 50)
{

    $client = new Google_Client();
    $client->setDeveloperKey(DEVELOPER_KEY);
    $youtube = new Google_Service_YouTube($client);

    $return = array();
    $list_id = array();

    try {
        $video_list = array();
        $continue = true;
        $pageToken = '';
        $count_item = 0;

        while ($continue) {
            if ($pageToken) {
                $params = array('q' => $keyword, 'maxResults' => 20, 'pageToken' => $pageToken);
            } else {
                $params = array('q' => $keyword, 'maxResults' => 20);
            }

            $searchResponse = $youtube->search->listSearch('id,snippet', $params);

            foreach ($searchResponse['items'] as $searchResult) {
                if ($searchResult['id']['kind'] == 'youtube#video') {
                    $count_item++;
                    $thumb = '';
                    if (isset($searchResult['snippet']['thumbnails']['medium']['url']) && !empty($searchResult['snippet']['thumbnails']['medium']['url'])) {
                        $thumb = $searchResult['snippet']['thumbnails']['medium']['url'];
                    } elseif (isset($searchResult['snippet']['thumbnails']['default']['url']) && !empty($searchResult['snippet']['thumbnails']['default']['url'])) {
                        $thumb = $searchResult['snippet']['thumbnails']['default']['url'];
                    }
                    $video_list[$searchResult['id']['videoId']] = array(
                        'id' => $searchResult['id']['videoId'],
                        'url' => 'https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'],
                        //'title' => $searchResult['snippet']['title'],
                        'image' => $thumb,
                    );
                    $list_id[] = $searchResult['id']['videoId'];
                    if ($count_item == $maxResults) {
                        $continue = false;
                        break;
                    }
                }
            }
            if (!isset($searchResponse['items'])) {
                $continue = false;
            }
            if (isset($searchResponse['nextPageToken']) && !empty($searchResponse['nextPageToken'])) {
                $pageToken = $searchResponse['nextPageToken'];
            } else {
                $continue = false;
                $pageToken = '';
            }
            //  Just get first page
            $continue = false;
        }

//        $comment_list = [];
//        $video_link = [];
//        foreach ($list_id as $video_id) {
//            if (in_array($video_id, $video_ids)) continue;
//            $video_link[] = "https://www.youtube.com/watch?v=$video_id";
//
//            $temp = get_commentThreads($youtube, $video_id);
//            if (!empty($temp)) {
//                $video_ids[] = $video_id;
//                foreach ($temp as $comment_id) {
//                    $comment_list[] = "https://www.youtube.com/watch?v=$video_id&lc=$comment_id";
//                }
//            }
//        }

//        $return['comment_list'] = $comment_list;
        $return['video_list'] = $video_list;
        return $return;

    } catch (Google_Service_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }

        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }

        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    } catch (Google_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }
        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }
        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    }
}

function get_comment($keyword, $maxVideos, $maxResults = 50)
{

    $client = new Google_Client();
    $client->setDeveloperKey(DEVELOPER_KEY);
    $youtube = new Google_Service_YouTube($client);

    $return = array();
    $list_id = array();
    $video_list = [];

    try {
        $continue = true;
        $pageToken = '';
        $count_item = 0;

        while ($continue) {
            if ($pageToken) {
                $params = array('q' => $keyword, 'maxResults' => 20, 'pageToken' => $pageToken);
            } else {
                $params = array('q' => $keyword, 'maxResults' => 20);
            }

            $searchResponse = $youtube->search->listSearch('id,snippet', $params);

            foreach ($searchResponse['items'] as $searchResult) {
                if ($searchResult['id']['kind'] == 'youtube#video') {
                    $count_item++;
                    $thumb = '';
                    if (isset($searchResult['snippet']['thumbnails']['medium']['url']) && !empty($searchResult['snippet']['thumbnails']['medium']['url'])) {
                        $thumb = $searchResult['snippet']['thumbnails']['medium']['url'];
                    } elseif (isset($searchResult['snippet']['thumbnails']['default']['url']) && !empty($searchResult['snippet']['thumbnails']['default']['url'])) {
                        $thumb = $searchResult['snippet']['thumbnails']['default']['url'];
                    }
                    $video_list[$searchResult['id']['videoId']] = array(
                        'id' => $searchResult['id']['videoId'],
//                        'url' => 'https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'],
                        //'title' => $searchResult['snippet']['title'],
                        'image' => $thumb,
                    );
                    $list_id[] = $searchResult['id']['videoId'];
                    if ($count_item == $maxResults) {
                        $continue = false;
                        break;
                    }
                }
            }
            if (!isset($searchResponse['items'])) {
                $continue = false;
            }
            if (isset($searchResponse['nextPageToken']) && !empty($searchResponse['nextPageToken'])) {
                $pageToken = $searchResponse['nextPageToken'];
            } else {
                $continue = false;
                $pageToken = '';
            }
            //  Just get first page
            $continue = false;
        }

        shuffle($list_id);
        $list_id = array_slice($list_id, 0, $maxVideos + 2);

        $comment_list = [];
        foreach ($list_id as $video_id) {
            $temp = get_commentThreads($youtube, $video_id);
            if (!empty($temp)) {
                foreach ($temp as $comment_id) {
                    $_temp['video_id'] = $video_id;
                    $_temp['url'] = "https://www.youtube.com/watch?v=$video_id&lc=$comment_id";
                    $_temp['image'] = $video_list[$video_id]['image'] ?? '';
                    $comment_list[] = $_temp;
                }
            }
        }

        $return['comment_list'] = $comment_list;
//        $return['video_list'] = $comment_list;
        return $return;

    } catch (Google_Service_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }

        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }

        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    } catch (Google_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }
        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }
        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    }
}


function sortByView($a, $b)
{
    return @$b['view'] - @$a['view'];
}

function get_commentThreads($service, $video_id, $maxResults = 10)
{
    $continue = true;
    $result = array();
    $pageToken = '';
    while ($continue) {
        if ($pageToken) {
//            $data_single = get_paged($url, $pageToken);
            $params = array('videoId' => $video_id, 'pageToken' => $pageToken, 'maxResults' => $maxResults, 'order' => 'relevance',
                //'searchTerms' => $keymain_text
            );
            $data_single = commentThreadsListByVideoId($service, 'snippet', $params);
        } else {
//            $data_single = make_call($url);
            $params = array('videoId' => $video_id, 'maxResults' => $maxResults, 'order' => 'relevance',
                //'searchTerms' => $keymain_text
            );
            $data_single = commentThreadsListByVideoId($service, 'snippet', $params);
        }

        if (isset($data_single['error'])) {
            $continue = false;
        }
        if (isset($data_single['items'])) {
            foreach ($data_single['items'] as $comments) {
                $result[] = $comments['id'];
            }
            // Stop next page
            $continue = false;
        } else {
            $continue = false;
        }

        if (isset($data_single['nextPageToken'])) {
            $pageToken = $data_single['nextPageToken'];
        } else {
            $pageToken = '';
            $continue = false;
        }
        // Just get first page
        $continue = false;
    }
    return $result;
}

function get_video_image($video_id)
{
    if (!$video_id) return '';

    $client = new Google_Client();
    $client->setDeveloperKey(DEVELOPER_KEY);
    $youtube = new Google_Service_YouTube($client);

    try {
        $response = $youtube->videos->listVideos('snippet', array(
            'id' => $video_id
        ));
        $videoArr = [];
        if (isset($response['items']) && !empty($response['items'])) {
            $videoArr = $response['items'][0];
        }
        $thumb = '';
        if (isset($videoArr['snippet']['thumbnails']['medium']['url']) && !empty($videoArr['snippet']['thumbnails']['medium']['url'])) {
            $thumb = $videoArr['snippet']['thumbnails']['medium']['url'];
        } elseif (isset($videoArr['snippet']['thumbnails']['default']['url']) && !empty($videoArr['snippet']['thumbnails']['default']['url'])) {
            $thumb = $videoArr['snippet']['thumbnails']['default']['url'];
        }
        return $thumb;
    } catch (Google_Service_Exception $e) {
    } catch (Google_Exception $e) {
    }

    return '';
}

function commentThreadsListByVideoId($service, $part, $params)
{
    try {
        $params = array_filter($params);
        $response = $service->commentThreads->listCommentThreads(
            $part,
            $params
        );
        return $response;
    } catch (Google_Service_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }

        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }

        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    } catch (Google_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }
        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }
        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    }
}

function videosListMultipleIds($service, $part, $params)
{
    try {
        $params = array_filter($params);
        $response = $service->videos->listVideos(
            $part,
            $params
        );
        return $response;
    } catch (Google_Service_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }

        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }

        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    } catch (Google_Exception $e) {
        if (strpos($e->getMessage(), "dailyLimitExceeded")) {
            echo "KEY đã hết lượt sử dụng. Chuyển sang key kế tiếp.";
        }
        if ((isset($_REQUEST['test']) && !empty($_REQUEST['test']))) {
            echo sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        }
        if (strpos($e->getMessage(), "commentsDisabled")) {
            return true;
        } else {
            return false;
        }
    }
}