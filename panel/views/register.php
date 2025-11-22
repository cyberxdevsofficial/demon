<?php require 'header.php'; ?>
<h2>Register</h2>
<?php if(isset($error)): ?><div class="error"><?php echo htmlentities($error); ?></div><?php endif; ?>
<form method="post" action="/register">
  <label>Username</label><br><input name="username"><br>
  <label>Email</label><br><input name="email"><br>
  <label>Password</label><br><input type="password" name="password"><br>
  <button type="submit">Register</button>
</form>
<?php require 'footer.php'; ?>
