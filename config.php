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
// MySQL Hostname
$sql_db_host = getenv('DB_HOST') ?: "127.0.0.1";
// MySQL Database User
$sql_db_user = getenv('DB_USER') ?: "root";
// MySQL Database Password
$sql_db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
// MySQL Database Name
$sql_db_name = getenv('DB_NAME') ?: "eroticsocialdb";

// Site URL
$site_url = getenv('SITE_URL') ?: "http://localhost:8085"; // e.g (http://example.com)

$auto_redirect = false;

// Purchase code
$purchase_code = "installed";

$siteEncryptKey = "e6c6b44784a96b79da93a1c62f2c8db671ad624b";
?>