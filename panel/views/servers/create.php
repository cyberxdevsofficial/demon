<?php require 'header.php'; ?>
<h2>Create Server</h2>
<form method="post" action="/servers/create">
  <label>Name</label><br><input name="name"><br>
  <label>Egg (template)</label><br><input name="egg" value="minecraft"><br>
  <button type="submit">Create</button>
</form>
<?php require 'footer.php'; ?>
