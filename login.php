<?php
session_start();
$hata='';
if(isset($_POST['eposta']) && isset($_POST['sifre'])){
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
if ($db->connect_errno)  die('ERROR' . $db->connect_error);


$db->set_charset("utf8");