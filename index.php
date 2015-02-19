<!DOCTYPE html>
<html lang="eng">
 <head>
  <title> My Blog </title>
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
 <a href="login.php">LOG IN</a>
 <hr />
<?php
$db = @new mysqli('localhost', 'root', '', 'blog');
if ($db->connect_errno) die('Bağlantı Hatası:' . $db->connect_error);


$db->set_charset("utf8");


$toplam        = $db->query("SELECT count(*) FROM blog");


$sayfa_sayisi = $toplam->fetch_row();


$toplam->close();


$blog  = $db->prepare("SELECT * FROM blog Order By blog_id DESC LIMIT ? OFFSET ?");


$yorum = $db->prepare("SELECT * FROM yorum WHERE blog_id = ?");

$limit = 2; 
$ofset = isset($_GET['id']) ? $_GET['id'] : 0;

$blog->bind_param("ii", $limit, $ofset);

$blog->execute();

$blog_sonuc = $blog->get_result();

while ($row = $blog_sonuc->fetch_array()) {

    
    if(strlen(strip_tags($row['yazi'])) >50)
    $row['yazi'] =substr(strip_tags($row['yazi']), 0, 50);

    
    echo "<div class='makale'>
         <h3>{$row['baslik']}</h3>
         <i>{$row['tarih']} date</i>
         <p> {$row['yazi']}  
		 . . .<a href='details.php?id={$row['blog_id']}'>More</a>
		 </p>";

    
    $yorum->bind_param('i', $row['blog_id']);

    
    $yorum->execute();

   
    $yorum_sonuc = $yorum->get_result();

   
    echo '<p>'.$yorum_sonuc->num_rows . ' comments</p>';
    echo "</div>\n";
}

if ($sayfa_sayisi[0] > $limit) {
    $x = 0;
    for ($i = 0; $i < $sayfa_sayisi[0]; $i += $limit) {
        $x++;
        echo "<a href='?id=$i'>[ $x ]</a>";
    }
}
$blog->close();
$yorum->close();
$db->close();
?>
</body>
</html>