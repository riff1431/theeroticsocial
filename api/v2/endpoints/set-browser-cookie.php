<?php
// +------------------------------------------------------------------------+
// | @author Arifur Rahman
// | @agency Huipper
// | @url https://huipper.com/
// | @github https://github.com/arifurfullstack
// +------------------------------------------------------------------------+
// | theeroticsocial - The Ultimate Social Networking Platform
// | Copyright (c) 2026 theeroticsocial. All rights reserved.
// +------------------------------------------------------------------------+
$response_data = array(
    'api_status' => 400,
);
if (!empty(Wo_GetUserFromSessionID($_GET['access_token']))) {
	$cookie = Wo_Secure($_GET['access_token']);
	$_SESSION['user_id'] = $cookie;
	setcookie("user_id", $cookie, time() + (10 * 365 * 24 * 60 * 60));
	header("Location: " . Wo_SeoLink('index.php?link1=get_news_feed'));
	exit();
}