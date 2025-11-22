<?php
require_once __DIR__.'/db.php';

function current_user() {
    return \$_SESSION['user'] ?? null;
}

// Servers CRUD
function create_server(\$name,\$egg,\$owner_id) {
    \$db = db();
    \$stmt = \$db->prepare('INSERT INTO servers (name,egg,owner_id,status) VALUES (?,?,?,?)');
    \$stmt->execute([\$name,\$egg,\$owner_id,'stopped']);
}

function list_servers_for_user(\$user_id) {
    \$db=db();
    \$stmt = \$db->prepare('SELECT * FROM servers WHERE owner_id = ?');
    \$stmt->execute([\$user_id]);
    return \$stmt->fetchAll(PDO::FETCH_ASSOC);
}

function list_all_servers() {
    \$db=db();
    \$stmt = \$db->query('SELECT * FROM servers');
    return \$stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_server(\$id) {
    \$db=db();
    \$stmt = \$db->prepare('SELECT * FROM servers WHERE id = ?');
    \$stmt->execute([\$id]);
    return \$stmt->fetch(PDO::FETCH_ASSOC);
}

function update_server(\$id,\$name,\$egg) {
    \$db=db();
    \$stmt = \$db->prepare('UPDATE servers SET name = ?, egg = ? WHERE id = ?');
    \$stmt->execute([\$name,\$egg,\$id]);
}

function delete_server(\$id) {
    \$db=db();
    \$stmt = \$db->prepare('DELETE FROM servers WHERE id = ?');
    \$stmt->execute([\$id]);
}
