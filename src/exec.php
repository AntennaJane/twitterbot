<?php

require __DIR__ . "/../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

const PATH_LAST_TWEET = __DIR__ . '/../var/last_tweet';
const PATH_PARAMETERS_INI = __DIR__ . '/../app/parameters.ini';

$ini = parse_ini_file(PATH_PARAMETERS_INI);

$connection = new TwitterOAuth($ini["CONSUMER_KEY"], $ini["CONSUMER_SECRET"], $ini["ACCESS_TOKEN"], $ini["ACCESS_TOKEN_SECRET"]);

touch(PATH_LAST_TWEET);
$id = file_get_contents(PATH_LAST_TWEET);

$parameters = [
    "count" => 1,
    "include_rts" => false,
    "trim_user" => true,
    "user_id" => $ini["USER_ID"],
];

if (!empty($id)) {
    // 前回取得時より200件超過のツイートをしていた場合は取りこぼす。取得間隔を縮めて対応すること
    $parameters["count"] = 200;
    $parameters["since_id"] = $id;
}

$contents = $connection->get("statuses/user_timeline", $parameters);

foreach (array_reverse($contents) as $content) {
    $id = $content->id;

    $keys = array_key_exists("KEYS", $ini) ? $ini["KEYS"] : [];
    foreach ($keys as $key) {
        if (preg_match($ini["SEARCHES"][$key], $content->text)) {
            $connection->post("statuses/update", [
                "in_reply_to_status_id" => $content->id,
                "status" => $ini["TWEETS"][$key],
                "trim_user" => true,
            ]);
        }
    }
}

file_put_contents(PATH_LAST_TWEET, $id);
