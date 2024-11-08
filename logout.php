<?php
session_start();
session_unset(); // Cancel session
session_destroy(); // Delete session
header("Location: index.html"); // Go to index.html
exit;
?>
