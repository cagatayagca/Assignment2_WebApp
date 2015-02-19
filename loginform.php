<?php require_once("login.php"); ?>
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
  <input type="password" name="sifre" />Pass<br />
  <input type="submit" value="Giriş" />
</form>
</body>
</html>