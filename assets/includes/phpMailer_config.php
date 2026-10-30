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
require 'assets/libraries/PHPMailer-Master/vendor/autoload.php';
require_once base64_decode('YXNzZXRzL2xpYnJhcmllcy9nb29nbGUvdmVuZG9yL3JpemUvdXJpLXRlbXBsYXRlL3NyYy9SaXplL1VyaVRlbXBsYXRlL05vZGUvTm9kZS5waHA=');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer;
