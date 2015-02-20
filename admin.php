<?php
ob_start(); 
session_start();
$db = @new mysqli('localhost', 'root', '', 'blog');
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
 <link href="style.css" type="text/css" rel="stylesheet" />
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
   if(isset($_GET['yorum_sil']))
  header('Location: details.php?id='.$_GET['id']);
  $stmt->close();
}

if(isset($_GET['guncelle'])){

 
  $stmt  = $db->prepare("SELECT * FROM blog WHERE blog_id=?");

  $stmt->bind_param("i", $_GET['guncelle']);

  $stmt->execute();

  $sonuc = $stmt->get_result();

  $row = $sonuc->fetch_array();

  echo '<h3>UPDATE</h3>
  <form method="post" action="admin.php">
  <input type="hidden" name="blog_id" value="'.$row['blog_id'].'"/>
  Başlık: <input type="text" name="baslik" value="'.$row['baslik'].'" />
  <br />Notification<br/>
  <textarea rows="5" cols="30" name="yazi">'.$row['yazi'].'</textarea>
  <br /><input type="submit" name="guncelle" value="Kaydet" />
  </form>';
  $stmt->close();
}else{
  echo '<h3>ADD</h3>
   <form method="post" action="admin.php">
   Header <input type="text" name="baslik" />
  <br />Notification<br/>
   <textarea rows="5" cols="30" name="yazi"></textarea>
   <br /><input type="submit" name="ekle" value="Kaydet" />
  </form>';
}

$blog  = $db->prepare("SELECT * FROM blog");


$blog->execute();


$blog_sonuc = $blog->get_result();


echo '<hr /><table border=1>';
while ($row = $blog_sonuc->fetch_array()) {
  echo "<tr>
  <td>{$row['baslik']}</td><td>
  <a href='?guncelle={$row['blog_id']}'>UPDATE</a>
  <a href='?sil={$row['blog_id']}' onclick=\"return confirm('DELETE?')\">DELETE</a>
  </td>
  </tr>\n";
}
echo '</table>';
$blog->close();
$db->close();
?>
</body>
</html>
<?php ob_end_flush(); ?>