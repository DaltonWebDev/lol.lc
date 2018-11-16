<?php
$reservedLinks = [
	"api"
];
if (!file_exists("links")) {
    mkdir("links", 0777, true);
}
if (!file_exists("links/.htaccess")) {
	file_put_contents("links/.htaccess", "Deny from all");
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE,PUT");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-type: application/json; charset=utf-8");
include("data.php");
$url = !empty($_POST["url"]) ? $_POST["url"] : false;
$link = !empty($_POST["link"]) ? strtolower($_POST["link"]) : false;
$data = false;
if ($url === false) {
	$error = "URL_MISSING";
} else if (filter_var($url, FILTER_VALIDATE_URL) === false) {
	$error = "URL_INVALID";
} else if ($link !== false && !ctype_alnum($link)) {
	$error = "LINK_INVALID";
} else if ($link !== false && in_array($link, $reservedLinks)) {
	$error = "LINK_RESERVED";
} else if ($link !== false && strlen($link) > 20) {
	$error = "LINK_LONG";
} else if ($link !== false && file_exists("links/$link.json")) {
	$error = "LINK_EXISTS";
} else {
	if ($link === false) {
		$link = bin2hex(random_bytes(3));
		while (file_exists("links/$link.json")) {
			$link = bin2hex(random_bytes(3));
		}
	}
	$ip = $_SERVER["REMOTE_ADDR"];
	$linkArray = [
		"time" => time(),
		"ip" => $ip,
		"url" => $url
	];
	file_put_contents("links/$link.json", json_encode($linkArray, JSON_UNESCAPED_SLASHES));
	$error = false;
	$data = $link;
}
$outputArray = [
	"error" => $error,
	"data" => $data
];
echo json_encode($outputArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
?>