<?php
ob_start(); 
session_start();
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
if ($db->connect_errno)  die('ERROR' . $db->connect_error);


$db->set_charset("utf8");

if(isset($_SESSION['uye'])){
  if($_SESSION['uye'] == 2){
     header('Location: index.php');
  }
}else{
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
 <head>
  <title> My Blog </title>
  <meta charset="utf-8" />
  <style>
   body{font-family: "Times New Roman", arial; font-size:12pt;}
   div{border:1px solid silver; margin:4px; padding:4px}
   .yorum div{border:0; border-top:1px solid silver}
</style>
 </head>
 <body>
 <a href="index.php">MAIN</a><br />

 <?php

if(isset($_POST['ekle'])){
  $sql ="INSERT INTO blog(baslik,yazi) VALUES(?,?)";

}else if(isset($_POST['guncelle'])){
  $sql ="UPDATE blog SET baslik=?,yazi=? WHERE blog_id=?";

}else if(isset($_GET['sil'])){
  $sql ="DELETE FROM blog WHERE blog_id=?";

}else if(isset($_GET['yorum_sil'])){
  $sql ="DELETE FROM yorum WHERE yorum_id=?";
  $_GET['sil']= $_GET['yorum_sil'];

}