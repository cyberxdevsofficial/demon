<?php require 'header.php'; ?>
<h2>Users</h2>
<?php
$stmt = db()->query('SELECT id,username,email,role,api_token FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<table class="table'><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Token</th></tr>';
foreach($users as $u) {
  echo '<tr>';
  echo '<td>'.$u['id'].'</td><td>'.htmlentities($u['username']).'</td><td>'.htmlentities($u['email']).'</td><td>'.$u['role'].'</td><td><code>'.$u['api_token'].'</code></td>';
  echo '</tr>';
}
echo '</table>';
?>
<?php require 'footer.php'; ?>
