<?php
require_once __DIR__.'/db.php';

function register_user($username,$email,$password) {
    if (!$username || !$email || !$password) return ['success'=>false,'message'=>'Missing fields'];
    \$db=db();
    \$stmt = \$db->prepare('SELECT id FROM users WHERE email = ?');
    \$stmt->execute([$email]);
    if (\$stmt->fetch()) return ['success'=>false,'message'=>'Email already used'];
    \$hash = password_hash(\$password, PASSWORD_DEFAULT);
    \$token = bin2hex(random_bytes(16));
    \$stmt = \$db->prepare('INSERT INTO users (username,email,password,role,api_token) VALUES (?,?,?,?,?)');
    \$stmt->execute([$username,$email,\$hash,'user',\$token]);
    return ['success'=>true];
}

function login_user($email,$password) {
    \$db=db();
    \$stmt = \$db->prepare('SELECT * FROM users WHERE email = ?');
    \$stmt->execute([$email]);
    \$u = \$stmt->fetch(PDO::FETCH_ASSOC);
    if (!\$u) return ['success'=>false,'message'=>'Invalid credentials'];
    if (!password_verify(\$password, \$u['password'])) return ['success'=>false,'message'=>'Invalid credentials'];
    \$_SESSION['user'] = ['id'=>$u['id'],'username'=>$u['username'],'email'=>$u['email'],'role'=>$u['role'],'api_token'=>$u['api_token']];
    return ['success'=>true];
}

function logout() { session_unset(); session_destroy(); }

function require_auth() {
    if (!isset(\$_SESSION['user'])) {
        header('Location: /login'); exit;
    }
}

function require_admin() {
    if (!isset(\$_SESSION['user']) || \$_SESSION['user']['role'] !== 'admin') {
        http_response_code(403); echo 'Forbidden'; exit;
    }
}
