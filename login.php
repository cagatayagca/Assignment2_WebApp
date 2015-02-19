<?php
session_start();
$hata='';
if(isset($_POST['eposta']) && isset($_POST['sifre'])){
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
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
   header('Location: admin.php');
  }else{
    $hata='<h3>Wrong e-mail or password</h3>';
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
 <head>
  <title> LOGIN </title>
  <meta charset="utf-8" />
 </head>
 <body>
 <h2>Giriş Yap</h2>
 <?php echo $hata; ?>
<form method="post" action="">
  <input type="text" name="eposta" />E-mail<br />
  <input type="text" name="sifre" />Pass<br />
  <input type="submit" value="Giriş" />
</form>
</body>
</html>