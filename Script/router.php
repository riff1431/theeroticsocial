<?php
chdir(__DIR__);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// 1. If static file exists, return false to let PHP serve it
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

// 2. Direct script access
if (preg_match('#^/(admincp|admin-cp)(\.php)?(/.*)?$#', $uri, $m)) {
    if (!empty($m[3])) {
        $_GET['page'] = trim($m[3], '/');
    }
    $_SERVER['SCRIPT_NAME'] = '/admincp.php';
    $_SERVER['PHP_SELF'] = '/admincp.php';
    require __DIR__ . '/admincp.php';
    exit;
}

if ($uri === '/requests.php' || $uri === '/_') {
    $_SERVER['SCRIPT_NAME'] = '/requests.php';
    $_SERVER['PHP_SELF'] = '/requests.php';
    require __DIR__ . '/requests.php';
    exit;
}

if ($uri === '/ajax_loading.php') {
    $_SERVER['SCRIPT_NAME'] = '/ajax_loading.php';
    $_SERVER['PHP_SELF'] = '/ajax_loading.php';
    require __DIR__ . '/ajax_loading.php';
    exit;
}

if (preg_match('#^/api(/.*)?$#', $uri, $m)) {
    if (!empty($m[1])) {
        $_GET['type'] = trim($m[1], '/');
    }
    $_SERVER['SCRIPT_NAME'] = '/api-v2.php';
    $_SERVER['PHP_SELF'] = '/api-v2.php';
    require __DIR__ . '/api-v2.php';
    exit;
}

if (preg_match('#^/api-v2(\.php)?$#', $uri)) {
    $_SERVER['SCRIPT_NAME'] = '/api-v2.php';
    $_SERVER['PHP_SELF'] = '/api-v2.php';
    require __DIR__ . '/api-v2.php';
    exit;
}

// 3. Routing rules from .htaccess
$rules = [
    '#^/password-reset/([^/]+)(/)?$#' => ['link1' => 'welcome', 'link2' => 'password_reset', 'user_id' => '$1'],
    '#^/start-up(/)?$#' => ['link1' => 'start-up'],
    '#^/watch/(.*)$#' => ['link1' => 'watch', 'id' => '$1'],
    '#^/watch$#' => ['link1' => 'watch'],
    '#^/saved-posts(/)?$#' => ['link1' => 'saved-posts'],
    '#^/unusual-login(/)?$#' => ['link1' => 'unusual-login'],
    '#^/activated/(.*)$#' => ['link1' => 'activate', 'link2' => '$1'],
    '#^/search/([^/]+)(/)?$#' => ['link1' => 'search', 'query' => '$1'],
    '#^/search(/)?$#' => ['link1' => 'search'],
    '#^/ads-create$#' => ['link1' => 'ads-create'],
    '#^/app/([^/]+)(/)?$#' => ['link1' => 'app', 'app_id' => '$1'],
    '#^/messages/([^/]+)(/)?$#' => ['link1' => 'messages', 'user' => '$1'],
    '#^/messages(/)?$#' => ['link1' => 'messages'],
    '#^/terms/([^/]+)(/)?$#' => ['link1' => 'terms', 'type' => '$1'],
    '#^/post/([^/]+)(/)?$#' => ['link1' => 'post', 'id' => '$1'],
    '#^/p/([^/]+)(/)?$#' => ['link1' => 'page', 'p' => '$1'],
    '#^/g/([^/]+)(/)?$#' => ['link1' => 'group', 'g' => '$1'],
    '#^/home(/)?$#' => ['link1' => 'home'],
    '#^/welcome(.*)$#' => ['link1' => 'welcome'],
    '#^/register(/)?$#' => ['link1' => 'register'],
    '#^/forgot-password(/)?$#' => ['link1' => 'forgot-password'],
    '#^/activate(/)?$#' => ['link1' => 'activate'],
    '#^/pages(/)?$#' => ['link1' => 'pages'],
    '#^/groups(/)?$#' => ['link1' => 'groups'],
    '#^/create-group(/)?$#' => ['link1' => 'create-group'],
    '#^/create-page(/)?$#' => ['link1' => 'create-page'],
    '#^/logout(/)?$#' => ['link1' => 'logout'],
    '#^/contact-us(/)?$#' => ['link1' => 'contact-us'],
    '#^/setting/([^/]+)/([^/]+)$#' => ['link1' => 'setting', 'user' => '$1', 'page' => '$2'],
    '#^/setting/([^/]+)$#' => ['link1' => 'setting', 'page' => '$1'],
    '#^/setting(/)?$#' => ['link1' => 'setting'],
    '#^/products/([^/]+)(/)?$#' => ['link1' => 'products', 'c_id' => '$1'],
    '#^/products(/)?$#' => ['link1' => 'products'],
    '#^/my-products(/)?$#' => ['link1' => 'my-products'],
    '#^/blogs(/)?$#' => ['link1' => 'blogs'],
    '#^/read-blog/([^/]+)(/)?$#' => ['link1' => 'read-blog', 'id' => '$1'],
    '#^/events(/)?$#' => ['link1' => 'events'],
    '#^/forum(/)?$#' => ['link1' => 'forum'],
    '#^/explore(/)?$#' => ['link1' => 'explore'],
    '#^/reels(/)?$#' => ['link1' => 'reels'],
    '#^/reels/([^/]+)(/)?$#' => ['link1' => 'reels', 'id' => '$1'],
    '#^/@([^/]+)(/)?$#' => ['link1' => 'timeline', 'u' => '$1'],
    '#^/([^/]+)/([^/]+)(/)?$#' => ['link1' => 'timeline', 'u' => '$1', 'type' => '$2'],
    '#^/$#' => ['link1' => 'home'],
];

$matched = false;
foreach ($rules as $pattern => $params) {
    if (preg_match($pattern, $uri, $matches)) {
        foreach ($params as $k => $v) {
            if (is_string($v) && strpos($v, '$') === 0) {
                $idx = (int)substr($v, 1);
                $_GET[$k] = $matches[$idx] ?? '';
            } else {
                $_GET[$k] = $v;
            }
        }
        $matched = true;
        break;
    }
}

if (!$matched && preg_match('#^/([^/]+)(/)?$#', $uri, $matches)) {
    $_GET['link1'] = 'timeline';
    $_GET['u'] = $matches[1];
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__ . '/index.php';
