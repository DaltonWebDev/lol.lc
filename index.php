<?php
$link = strtolower(ltrim($_SERVER["REQUEST_URI"], "/"));
if ($link === "") {
	echo file_get_contents("pages/home.html");
} else {
	if (file_exists("api/links/$link.json")) {
		$json = json_decode(file_get_contents("api/links/$link.json"), true);
		$url = $json["url"];
		echo str_replace(
			array("!url"),
			array($url),
			file_get_contents("pages/redirecting.html")
		);
	} else {
		echo "This Link Doesn't Exist";
	}
}
?>