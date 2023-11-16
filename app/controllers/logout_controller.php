<?php
session_start();
$_SESSION = array();
session_destroy();
header("location: /gabinet/index.php");
exit;
