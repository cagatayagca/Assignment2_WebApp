<?php
session_start();
$hata='';
if(isset($_POST['eposta']) && isset($_POST['sifre'])){
$db = @new mysqli('localhost', 'root','', 'blog');
if ($db->connect_errno)  die('ERROR' . $db->connect_error);


$db->set_charset("utf8");

$stmt  = $db->prepare("SELECT * FROM uye WHERE email=? AND sifre=MD5(?)");
if ($stmt === false) die('ERROR'. $db->error);

$stmt->bind_param("ss", $_POST['eposta'],$_POST['sifre']);

$stmt->execute();


$sonuc = $stmt->get_result();


if($sonuc->num_rows){
   $row = $sonuc->fetch_array();
   $_SESSION['uye'] = $row['durum'];
   $_SESSION['ad']  = $row['ad'];
   $_SESSION['status']  = 1;
   header('Location: admin.php');
  }else{
    $hata='<h3>Wrong e-mail or password</h3>';
  }
}
?>