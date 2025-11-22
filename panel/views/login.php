<?php require 'header.php'; ?>
<h2>Login</h2>
<?php if(isset($error)): ?><div class="error"><?php echo htmlentities($error); ?></div><?php endif; ?>
<form method="post" action="/login">
  <label>Email</label><br><input name="email"><br>
  <label>Password</label><br><input type="password" name="password"><br>
  <button type="submit">Login</button>
</form>
<?php require 'footer.php'; ?>
