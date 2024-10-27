<?php
session_start();
session_unset(); // Cancel session
session_destroy(); // Delete session
header("Location: index.php"); // Go to index.php
exit;
?>
