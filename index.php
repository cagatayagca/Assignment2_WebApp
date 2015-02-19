<?php 

include ("login.php") ?>
<!DOCTYPE html>
<html lang="eng">


 <head>


  <title> My Blog </title>
  <meta charset="utf-8" />
  <style>
 
</style>
 <link href="style.css" type="text/css" rel="stylesheet" />
 </head>
 <body>
 <div id="container">
 <?php 
 if(!isset($_SESSION['status']) || $_SESSION['status'] != 1 ) 
    echo "<a href='loginform.php'>LOG IN</a>";
 elseif(isset($_SESSION['status']) && $_SESSION['status'] == 1) 
    echo "Welcome " . $_SESSION['ad'] ." <a href = 'logout.php'>[Logout]</a>";
    echo " <a href = 'admin.php'>Admin Panel</a>";  
  ?>
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

$limit = 5; 
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
</div>
</body>
</html>