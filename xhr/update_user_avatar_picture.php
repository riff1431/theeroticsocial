<?php
if ($f == 'update_user_avatar_picture') {
    $data = array('status' => 400, 'error' => 'No image uploaded');
    if (isset($_FILES['avatar']['name'])) {
        if (!empty($_FILES['avatar']['error']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $data = array(
                'status' => 400,
                'error' => 'Upload failed (error code: ' . $_FILES['avatar']['error'] . '). Please ensure the image is under 96MB.'
            );
        } else {
            $ai_post = 0;
            if ($wo['config']['ai_user_system'] == 1 && !empty($_POST['ai_post']) && $_POST['ai_post'] == 'on') {
                $ai_post = 1;
            }
            $upload = Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['user_id'], '', $ai_post);
            if ($upload === true) {
                $img  = Wo_UserData($_POST['user_id']);
                $data = array(
                    'status' => 200,
                    'img' => $img['avatar'] . '?cache=' . rand(11, 22),
                    'img_or' => $img['avatar_org'],
                    'avatar_full' => Wo_GetMedia($img['avatar_full']) . '?cache=' . rand(11, 22),
                    'avatar_full_or' => $img['avatar_full'],
                    'big_text' => $wo['lang']['looks_good'],
                    'small_text' => $wo['lang']['looks_good_des']
                );
            } else {
                $data = is_array($upload) ? $upload : array('status' => 400, 'error' => 'Unsupported image format or processing error.');
            }
        }
    }
    Wo_CleanCache();
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
