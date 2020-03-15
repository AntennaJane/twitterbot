<?php

require __DIR__ . "/../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$ini = parse_ini_file(__DIR__ . "/../app/parameters.ini");

$connection = new TwitterOAuth($ini["CONSUMER_KEY"], $ini["CONSUMER_SECRET"], $ini["ACCESS_TOKEN"], $ini["ACCESS_TOKEN_SECRET"]);
$contents = $connection->get("statuses/user_timeline", [
    "include_rts" => false,
    "trim_user" => true,
    "user_id" => $ini["USER_ID"],
]);

foreach (array_reverse($contents) as $content) {
    echo $content->id;
    echo PHP_EOL;
    echo $content->text;
    echo PHP_EOL;
}
