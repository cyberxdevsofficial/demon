<?php
if (isset($_GET['theme'])) { $_SESSION['theme'] = $_GET['theme']; header('Location: /'); exit; }
require 'header.php';
?>
<h1>Welcome to DemonClassic</h1>
<p>Standalone panel without Wings. Demo admin created with email <b>admin@demonclassic.local</b> and password <b>admin123</b></p>
<?php require 'footer.php'; ?>
