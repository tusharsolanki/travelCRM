<?php //Created  By VIRU
require_once('../application/config/database.php');
 
$server_link = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']) or die(mysql_error());
$db_link = mysql_select_db($db['default']['database']) or die(mysql_error());
?>