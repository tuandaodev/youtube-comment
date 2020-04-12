<?php

require_once 'config.php';
require_once 'google-api/vendor/autoload.php';

function get_video(&$video_ids, $keyword, $maxResults = 20, $minview = 0, $minlike = 0, $mincomment = 0, $published_at = '01-01-2001')
{

    $client = new Google_Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
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
                $params = array('q' => $keyword, 'maxResults' => 50, 'pageToken' => $pageToken);
            } else {
                $params = array('q' => $keyword, 'maxResults' => 50);
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
                    $check_id = true;
                    if (!empty($video_ids) && in_array($searchResult['id']['videoId'], $video_ids)) {
                        $check_id = false;
                    }
                    if ($check_id) {
                        $video_list[$searchResult['id']['videoId']] = array(
                            'id' => $searchResult['id']['videoId'],
                            //'title' => $searchResult['snippet']['title'],
                            'thumb' => $thumb,
                        );
                        $list_id[] = $searchResult['id']['videoId'];
                    }
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


function sortByView($a, $b)
{
    return @$b['view'] - @$a['view'];
}

function get_commentThreads($service, $video_id, $maxResults = 20)
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
    }

    return $result;
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