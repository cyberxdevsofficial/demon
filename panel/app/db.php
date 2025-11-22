<?php
// Simple SQLite DB connection and helper functions
$dbfile = __DIR__ . '/../data/dc.sqlite';
$init = !file_exists($dbfile);
$db = new PDO('sqlite:' . $dbfile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($init) {
    // create tables
    $db->exec("""CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT,
        email TEXT UNIQUE,
        password TEXT,
        role TEXT,
        api_token TEXT
    );
    CREATE TABLE servers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        egg TEXT,
        owner_id INTEGER,
        status TEXT DEFAULT 'stopped',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    """);
    // create admin user
    \$pass = password_hash('admin123', PASSWORD_DEFAULT);
    \$token = bin2hex(random_bytes(16));
    \$stmt = \$db->prepare('INSERT INTO users (username,email,password,role,api_token) VALUES (?,?,?,?,?)');
    \$stmt->execute(['admin','admin@demonclassic.local',\$pass,'admin',\$token]);
}
function db() { global \$db; return \$db; }
