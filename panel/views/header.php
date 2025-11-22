<?php
\$user = \$_SESSION['user'] ?? null;
\$theme = \$_SESSION['theme'] ?? 'light';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>DemonClassic Panel</title>
<link rel="stylesheet" href="/static/css/<?php echo \$theme; ?>.css">
</head>
<body>
<nav class="nav">
  <a href="/">Home</a> |
  <?php if(!\$user): ?>
    <a href="/login">Login</a> | <a href="/register">Register</a>
  <?php else: ?>
    <a href="/dashboard">Dashboard</a> |
    <a href="/servers">My Servers</a> |
    <?php if(\$user['role']==='admin'): ?><a href="/admin">Admin</a> |<?php endif; ?>
    <a href="/logout">Logout (<?php echo htmlentities(\$user['username']); ?>)</a>
  <?php endif; ?>
  <span style="float:right;">
    Theme:
    <a href="?theme=light">Light</a> |
    <a href="?theme=dark">Dark</a>
  </span>
</nav>
<div class="container">
