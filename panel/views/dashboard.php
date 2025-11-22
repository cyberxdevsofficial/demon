<?php
require 'header.php';
$user = $_SESSION['user'];
?>
<h2>Dashboard</h2>
<p>Welcome, <?php echo htmlentities($user['username']); ?>.</p>
<ul>
<li><a href="/servers">My Servers</a></li>
<?php if($user['role'] === 'admin'): ?>
<li><a href="/admin">Admin Dashboard</a></li>
<?php endif; ?>
<li>API Token: <code><?php echo htmlentities($user['api_token']); ?></code></li>
</ul>
<?php require 'footer.php'; ?>
