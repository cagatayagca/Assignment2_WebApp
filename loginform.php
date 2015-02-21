<?php require_once("login.php"); ?>
<!DOCTYPE html>
<html lang="tr">
 <head>
  <title> LOGIN </title>
  <meta charset="utf-8" />
  <link href="css/style.css" type="text/css" rel="stylesheet" />
 </head>
 <body>
 <a href="index.php"><div id="header"></div></a>
 <div id="container">
 <a href="index.php">MAIN PAGE</a><br />
 <h2>Log In</h2>
 <?php echo $hata; ?>
 <div id="meta">
<form method="post" action="">
  <input type="text" name="eposta" />E-mail<br />
  <input type="password" name="sifre" />Pass<br />
  <input type="submit" value="LOGIN" />
</form>
</div>
</div>
</body>
</html>