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

function getLowestPrice($apiKey, $appid, $country = "us") {
    $lookup = lookupById($apiKey, $appid);
    if(!$lookup["found"])
        return [];

    $id = $lookup["game"]["id"];

    $body = [$id];

    $ch = curl_init(getUrl("games/historylow", 1, [
        "key" => $apiKey,
        "country" => $country
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    $response = json_decode(curl_exec($ch), true);
    return $response;
}

function getUrl($interfaceName, $version=1, $args=[]) {
    return URL . $interfaceName . "/v".$version . "?" . http_build_query($args);
}
?>