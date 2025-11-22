<?php require 'header.php'; 
$server = \$server ?? get_server(intval(basename(dirname(__FILE__))));
?>
<h2>Edit Server</h2>
<form method="post" action="">
  <label>Name</label><br><input name="name" value="<?php echo htmlentities(\$server['name']); ?>"><br>
  <label>Egg</label><br><input name="egg" value="<?php echo htmlentities(\$server['egg']); ?>"><br>
  <button type="submit">Save</button>
</form>
<?php require 'footer.php'; ?>
