<?php session_start(); ?>
<!DOCTYPE html>
<html lang="tr">
 <head>
  <title> Blog Details </title>
  <meta charset="utf-8" />
  <style>
   body{font-family: "Times New Roman", arial; font-size:13pt;}
   div{ margin:4px; padding:4px}
   .makale{border:1px solid silver;}
   h3,h4{text-decoration:underline;margin:4px;font-size:16pt;}
   h4{font-size:13pt;}
   .yorum{border:0; border-top:1px dashed silver}
  </style>
 </head>

 <body>
<a href="index.php">MAIN PAGE</a><br />
<?php
$db = @new mysqli('localhost', 'root', '', 'blog');
if ($db->connect_errno) die('Bağlantı Hatası:' . $db->connect_error);

$db->set_charset("utf8");

$blog  = $db->prepare("SELECT * FROM blog WHERE blog_id = ?");

$yorum = $db->prepare("SELECT * FROM yorum WHERE blog_id = ?");

$istek = isset($_GET['id']) ? $_GET['id'] : die('Hatalı istek');

$blog->bind_param("i", $istek);

$blog->execute();

$blog_sonuc = $blog->get_result();

while ($row = $blog_sonuc->fetch_array()) {
    echo "<div class='makale'>
         <h3>{$row['baslik']}</h3>
         <i>{$row['tarih']} date</i>
         <p> {$row['yazi']}  </p>";

    $yorum->bind_param('i', $row['blog_id']);

    $yorum->execute();

    $yorum_sonuc = $yorum->get_result();

    if ($yorum_sonuc->num_rows) {
        echo '<hr /><p>'.$yorum_sonuc->num_rows . ' comments</p>';
    }

while ($row2 = $yorum_sonuc->fetch_array()) {
       $uye=isset($_SESSION['uye']) ? $_SESSION['uye'] : null;
       if($uye == 1){
           $yorum_id =$row2['yorum_id'];
           $blog_id=$row['blog_id'];
       $sil ="<a href='admin.php?yorum_sil=$yorum_id&id=$blog_id'>DELETE</a>";
       } 
        echo "<div class='yorum'>$sil<b> {$row2['yazan']} </b>
              <i> {$row2['tarih']} </i>
              <div> {$row2['mesaj']}</div> </div>";
    }

   
    echo '<p>Yorum Yap<form method="post" action="comment.php">
      <input type="hidden" name="blog_id" value="' . $row['blog_id'] . '"/>
      Ad Soyad: <input type="text" name="yazan" maxlength="10" /><br />
      Yorumunuz: <br />
      <textarea rows="2" cols="30" name="mesaj"></textarea><br />
      <input type="submit" name="yorum" value="Kaydet"/>
      </form></p>';
    echo "</div>\n";
}


$blog->close();
$yorum->close();
$db->close();
?>
</body>
</html>