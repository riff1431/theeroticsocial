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
    'api_status' => 400
);
if (empty($_POST['password'])) {
    $error_code    = 4;
    $error_message = 'password (POST) is missing';
}
if (empty($error_code)) {
    $delete     = Wo_DeleteUser($wo['user']['user_id']);
    if ($delete) {
        $response_data = array(
            'api_status' => 200,
            'message' => "User successfully deleted."
        );
    }
}