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

if(isset($_POST['ekle']) || isset($_POST['guncelle']) ){

 
  $stmt = $db->prepare($sql);
  if ($stmt === false) die('ERROR'. $db->error);

  if($_POST['ekle'])
  $stmt->bind_param("ss", $_POST['baslik'],$_POST['yazi']);

  if($_POST['guncelle'])
  $stmt->bind_param("ssi", $_POST['baslik'],$_POST['yazi'],$_POST['blog_id']);

  $stmt->execute();
    if($db->affected_rows < 1){
     die('COULDNT ADD');
  }
  $stmt->close();
}else if(isset($_GET['sil']) || isset($_GET['yorum_sil'])){


  $stmt = $db->prepare($sql);
  if ($stmt === false) die('ERROR'. $db->error);

 
  $stmt->bind_param("i", $_GET['sil']);


  $stmt->execute();
  if($db->affected_rows < 1){
     die('Post wasnt deleted');
  }