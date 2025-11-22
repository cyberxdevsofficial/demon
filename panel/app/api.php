<?php
// Simple token-based API for DemonClassic
require_once __DIR__ . '/db.php';

$path = substr($_SERVER['REQUEST_URI'],5); // remove /api/
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

function auth_api() {
    // token via Authorization: Bearer <token> or ?token=
    \$headers = getallheaders();
    \$token = null;
    if (isset(\$headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.*)/', \$headers['Authorization'], \$m)) \$token = \$m[1];
    }
    if (!\$token && isset(\$_GET['token'])) \$token = \$_GET['token'];
    if (!\$token) { http_response_code(401); echo json_encode(['error'=>'Missing token']); exit; }
    \$db = db();
    \$stmt = \$db->prepare('SELECT * FROM users WHERE api_token = ?');
    \$stmt->execute([\$token]);
    \$u = \$stmt->fetch(PDO::FETCH_ASSOC);
    if (!\$u) { http_response_code(401); echo json_encode(['error'=>'Invalid token']); exit; }
    return \$u;
}

if (\$path === 'me' && \$method === 'GET') {
    \$u = auth_api();
    unset(\$u['password']);
    echo json_encode(\$u);
    exit;
}

if (\$path === 'servers' && \$method === 'GET') {
    \$u = auth_api();
    if (\$u['role'] === 'admin') {
        \$s = db()->query('SELECT * FROM servers')->fetchAll(PDO::FETCH_ASSOC);
    } else {
        \$stmt = db()->prepare('SELECT * FROM servers WHERE owner_id = ?');
        \$stmt->execute([\$u['id']]);
        \$s = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode(['servers'=>\$s]);
    exit;
}

http_response_code(404); echo json_encode(['error'=>'Not found']);
