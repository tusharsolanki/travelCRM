<?php
$viewname = $this->router->uri->segments[2];
session_start();
session_destroy(); //destroy the session
header("location:index.php"); //to redirect back to "index.php" after logging out
exit();
?>