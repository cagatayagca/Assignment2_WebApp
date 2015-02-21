<?php session_start(); ?>
<!DOCTYPE html>
<html lang="tr">
 <head>

  <title> Blog Details </title>
  <meta charset="utf-8" />
  
  <style>

  </style>
  <link href="css/style.css" type="text/css" rel="stylesheet" />
     <link href="css/font-awesome.min.css" rel="stylesheet">
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

 </head>

 <body>
<a href="index.php"><div id="header"></div></a>
<div id="container">
<a href="index.php">MAIN PAGE</a><br />
<?php
$db = @new mysqli('localhost', 'root', '', 'blog');
if ($db->connect_errno) die('Bağlantı Hatası:' . $db->connect_error);

$db->set_charset("utf8");

$blog  = $db->prepare("SELECT b.blog_id, b.baslik, b.yazi, b.tarih, u.ad FROM blog b inner join uye u on b.uye_id = u.uye_id WHERE blog_id = ?");

$yorum = $db->prepare("SELECT * FROM yorum WHERE blog_id = ?");

$istek = isset($_GET['id']) ? $_GET['id'] : die('Hatalı istek');

$blog->bind_param("i", $istek);

$blog->execute();

$blog_sonuc = $blog->get_result();

while ($row = $blog_sonuc->fetch_array()) {
    echo "<div class='makale'>
         <div id='post-header'><h3>{$row['baslik']}</h3></div>
         <div id='meta'><i class='fa fa-calendar'></i><span>{$row['tarih']}</span><i class='fa fa-user'></i><span>{$row['ad']}<span style='float:right;'><a href = 'admin.php'>Düzenle/Sil</a></span></div>
         <div id='post'><p> {$row['yazi']} </p> </div>
         </div>";

    $yorum->bind_param('i', $row['blog_id']);

    $yorum->execute();

    $yorum_sonuc = $yorum->get_result();

    if ($yorum_sonuc->num_rows) {
        echo '<p>'.$yorum_sonuc->num_rows . ' comments</p>';
    }
global $sil;
while ($row2 = $yorum_sonuc->fetch_array()) {
       $uye=isset($_SESSION['uye']) ? $_SESSION['uye'] : null;
       if($uye == 1){
           $yorum_id =$row2['yorum_id'];
           $blog_id=$row['blog_id'];
       $sil ="<a style = 'float:right;' href='admin.php?yorum_sil=$yorum_id&id=$blog_id'><i class='fa fa-times-circle'></i></a>";
       } 

        echo "
          <div id='comment'>
          <div id='comment-meta'>
          <i class='fa fa-user'></i><span>{$row2['yazan']}</span><i class='fa fa-calendar'></i><span>{$row2['tarih']} </span>
           <span>$sil</span>
           </div>
            <div id='comment-body'> {$row2['mesaj']}</div> </div><hr>";
    }

   
    echo '<div id="reply"><h3>Leave A Comment</h3><br>
          <form method="post" action="comment.php">
            <input type="hidden" name="blog_id" value="' . $row['blog_id'] . '"/>
            <dt>Name</dt> 
            <dd><input type="text" name="yazan" maxlength="10" /></dd>
            <dt>Comment</dt>
            <dd><textarea rows="2" cols="30" name="mesaj"></textarea></dd>
            <input type="submit" name="yorum" value="Submit "/>
          </form></div>';
}


$blog->close();
$yorum->close();
$db->close();
?>
 
</div>
</body>
</html>