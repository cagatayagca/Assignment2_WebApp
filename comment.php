<?php
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
if ($db->connect_errno)  die('Bağlantı Hatası:' . $db->connect_error);


$db->set_charset("utf8");