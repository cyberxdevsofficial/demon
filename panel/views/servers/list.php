<?php require 'header.php'; ?>
<h2>My Servers</h2>
<p><a href="/servers/create">Create Server</a></p>
<?php
$servers = list_servers_for_user($_SESSION['user']['id']);
if (!$servers) { echo '<p>No servers yet.</p>'; }
else {
  echo '<table class="table'><tr><th>ID</th><th>Name</th><th>Egg</th><th>Status</th><th>Actions</th></tr>';
  foreach($servers as $s) {
    echo '<tr>';
    echo '<td>'.$s['id'].'</td>';
    echo '<td>'.htmlentities($s['name']).'</td>';
    echo '<td>'.htmlentities($s['egg']).'</td>';
    echo '<td>'.$s['status'].'</td>';
    echo '<td><a href="/servers/'.$s['id'].'/edit">Edit</a> 
      <form method="post" action="/servers/'.$s['id'].'/delete" style="display:inline"><button>Delete</button></form></td>';
    echo '</tr>';
  }
  echo '</table>';
}
?>
<?php require 'footer.php'; ?>
