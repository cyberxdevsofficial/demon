<?php require 'header.php'; ?>
<h2>All Servers</h2>
<?php
$servers = list_all_servers();
if (!$servers) echo '<p>No servers.</p>';
else {
  echo '<table class="table'><tr><th>ID</th><th>Name</th><th>Egg</th><th>Owner</th><th>Status</th></tr>';
  foreach($servers as $s) {
    $owner = db()->prepare('SELECT username FROM users WHERE id = ?');
    $owner->execute([$s['owner_id']]);
    $o = $owner->fetch(PDO::FETCH_ASSOC);
    echo '<tr><td>'.$s['id'].'</td><td>'.htmlentities($s['name']).'</td><td>'.htmlentities($s['egg']).'</td><td>'.htmlentities($o['username']).'</td><td>'.$s['status'].'</td></tr>';
  }
  echo '</table>';
}
?>
<?php require 'footer.php'; ?>
