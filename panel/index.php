<?php
session_start();
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/helpers.php';

// Simple routing
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/' || $path === '/index.php') {
    require 'views/home.php';
    exit;
}

if ($path === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'views/register.php'; exit;
}
if ($path === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = register_user($username,$email,$password);
    if ($res['success']) { header('Location: /login'); exit; }
    $error = $res['message']; require 'views/register.php'; exit;
}

if ($path === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'views/login.php'; exit;
}
if ($path === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = login_user($email,$password);
    if ($res['success']) { header('Location: /dashboard'); exit; }
    $error = $res['message']; require 'views/login.php'; exit;
}

if ($path === '/logout') {
    logout(); header('Location: /'); exit;
}

if ($path === '/dashboard') {
    require_auth();
    require 'views/dashboard.php'; exit;
}

// Admin area
if (strpos($path,'/admin') === 0) {
    require_auth();
    require_admin();
    // Admin routing
    if ($path === '/admin') { require 'views/admin/index.php'; exit; }
    if ($path === '/admin/users') { require 'views/admin/users.php'; exit; }
    if ($path === '/admin/servers') { require 'views/admin/servers.php'; exit; }
}

// Servers CRUD
if ($path === '/servers' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_auth(); require 'views/servers/list.php'; exit;
}
if ($path === '/servers/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_auth(); require 'views/servers/create.php'; exit;
}
if ($path === '/servers/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_auth();
    $name = $_POST['name'] ?? '';
    $egg = $_POST['egg'] ?? '';
    $owner_id = $_SESSION['user']['id'];
    create_server($name,$egg,$owner_id);
    header('Location: /servers'); exit;
}
if (preg_match('#^/servers/([0-9]+)/edit$#',$path,$m) && $_SERVER['REQUEST_METHOD']==='GET') {
    require_auth();
    $server = get_server($m[1]);
    if (!$server) { http_response_code(404); echo 'Not found'; exit; }
    require 'views/servers/edit.php'; exit;
}
if (preg_match('#^/servers/([0-9]+)/edit$#',$path,$m) && $_SERVER['REQUEST_METHOD']==='POST') {
    require_auth();
    $name = $_POST['name'] ?? '';
    $egg = $_POST['egg'] ?? '';
    update_server($m[1],$name,$egg);
    header('Location: /servers'); exit;
}
if (preg_match('#^/servers/([0-9]+)/delete$#',$path,$m) && $_SERVER['REQUEST_METHOD']==='POST') {
    require_auth();
    delete_server($m[1]);
    header('Location: /servers'); exit;
}

// API endpoints (token based)
if (strpos($path,'/api/') === 0) {
    require_once __DIR__.'/app/api.php';
    exit;
}

// 404
http_response_code(404);
echo 'Not Found';
