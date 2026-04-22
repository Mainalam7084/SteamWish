<?php
const URL = "https://api.isthereanydeal.com/";

function lookupById($apiKey, $appid) {
    return json_decode(file_get_contents(
        getUrl("games/lookup", 1, [
            "appid" => $appid,
            "key" => $apiKey
        ])
    ), true);
}

function getPriceHistory($apiKey, $appid, $since, $country = "us") { 
    $result = lookupById($apiKey, $appid);

    if($result['found'] === false)
        return null;

    $id = $result["game"]["id"];

    return json_decode(file_get_contents(
        getUrl("games/history", 2,[
            "id" => $id,
            "key" => $apiKey,
            "country" => $country,
            "since" => $since->format("c")
        ])
    ), true);
}

function getUrl($interfaceName, $version=1, $args=[]) {
    return URL . $interfaceName . "/v".$version . "?" . http_build_query($args);
}
?>