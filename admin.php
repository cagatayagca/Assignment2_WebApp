<?php
ob_start(); 
session_start();
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
if ($db->connect_errno)  die('ERROR' . $db->connect_error);


$db->set_charset("utf8");
