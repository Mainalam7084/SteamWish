<?php
const WEBAPI_URL = "https://store.steampowered.com/api/";
const STEAMWORKS_URL = "https://api.steampowered.com/";
const SEARCH_URL = "https://steamcommunity.com/actions/SearchApps/";

function getAppList($api_key, $max_results=10000,$last_appid=0) {
	$applist = json_decode(file_get_contents(getSteamworksUrl(
		"IStoreService",
		"GetAppList",
		1,
		[
			"key" => $api_key,
			"max_results" => $max_results,
			"last_appid" => $last_appid
		]
	)), true);

	if(!$applist)
		return [];

	return $applist['response'];
}

function getAppDetails($appid, $contentCountry = "us") {
	return json_decode(file_get_contents(getWebApiUrl(
		"appdetails", 
		[
			"appids" => $appid,
			"cc" => $contentCountry
		]
	)), true);
}

function getSearch($query) {
	return json_decode(file_get_contents(getSearchUrl($query)),true);
}

//Gets a url formatted like so: 'http://api.steampowered.com/<interfaceName>/<method>/v<version>/?args
function getSteamworksUrl($interfaceName, $method, $version=1, $args=[]) {
	return STEAMWORKS_URL . $interfaceName . "/" . $method . "/v" . $version . "/?" . http_build_query($args);
}

//Gets a url formatted like so: http://store.steampowered.com/api/<endpoint>/?args
function getWebApiUrl($endpoint, $args=[]) {
	return WEBAPI_URL . $endpoint . "/?" . http_build_query($args);
}

function getSearchUrl($query) {
	return SEARCH_URL . $query ;
}
?>
