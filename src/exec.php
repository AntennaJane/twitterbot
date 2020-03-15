<?php

require __DIR__ . "/../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$ini = parse_ini_file(__DIR__ . "/../app/parameters.ini");

$connection = new TwitterOAuth($ini["CONSUMER_KEY"], $ini["CONSUMER_SECRET"], $ini["ACCESS_TOKEN"], $ini["ACCESS_TOKEN_SECRET"]);
$content = $connection->get("account/verify_credentials");

var_dump($content);
