<?php 

include ("login.php") ?>
<!DOCTYPE html>
<html lang="eng">


 <head>

  <title> My Blog </title>
  <meta charset="utf-8" />
  <style>
 
</style>
 <link href="css/style.css" type="text/css" rel="stylesheet" />
 <link href="css/font-awesome.min.css" rel="stylesheet">
   <!-- Include jQuery -->
  <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>

  <!-- Include jQuery Popup Overlay -->
  <script src="http://vast-engineering.github.io/jquery-popup-overlay/jquery.popupoverlay.js"></script>
 </head>
 <body>
 <a href="index.php"><div id="header"></div></a>
 <div id="container">
 <div id="navigation">
 <?php 
 if(!isset($_SESSION['status']) || $_SESSION['status'] != 1 ) 
    echo "<a href='loginform.php'>LOG IN</a>";
 elseif(isset($_SESSION['status']) && $_SESSION['status'] == 1) 
    echo "<ul>";
    echo "<li><a href='#'>Welcome " . $_SESSION['ad'] ."</a></li>";
    echo "<li><a href = 'admin.php'>Admin Panel</a></li>";
    echo "<li><a class='new_post_popup_open' href='#'>New Post</a></li>";
    echo "<li style='float:right;border-left: 1px solid #ececee;border-right: 0;margin-right: -55px;'><a href = 'logout.php'>Logout</a></li>";
    echo "</ul>";
  ?>
</div>

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
         <div id='post-header'><h3><a href='details.php?id={$row['blog_id']}'>{$row['baslik']}</a></h3></div>
         <div id='meta'><i class='fa fa-calendar'></i><span>{$row['tarih']}</span></div>
         <span><p> {$row['yazi']}  
		 <br><a href='details.php?id={$row['blog_id']}'>[More]</a>
		 </p></span>";

    
    $yorum->bind_param('i', $row['blog_id']);

    
    $yorum->execute();

   
    $yorum_sonuc = $yorum->get_result();

   
    echo '<p>'.$yorum_sonuc->num_rows . ' comments</p>';
    echo "</div>\n";
}

echo "<div style='text-align:center;margin: -20px auto 0 auto;'><ul class='pagination'>";
if ($sayfa_sayisi[0] > $limit) {
    $x = 0;
    for ($i = 0; $i < $sayfa_sayisi[0]; $i += $limit) {
        $x++;
        echo "<li><a href='?id=$i'>$x</a></li>";
    }
}
echo "</ul></div>";
$blog->close();
$yorum->close();
$db->close();
?>



  <div id="new_post_popup">

<form method="post" action="admin.php">
   <dl>
  <dt> Title:</dt> 
  <dd><input type="text" name="baslik" /></dd>
  <dt>Text:</dt>
  <dd><textarea rows="5" cols="30" name="yazi"></textarea></dd>
  </dl>
  <input style="margin-left:170px"  type="submit" name="ekle" value="Submit" />
  </form>
    <a style="float:right;" class="new_post_popup_close" href="#"><span id="popup-close"><i class="fa fa-times"></i></span></a>

  </div>
 <script>
    $(document).ready(function() {

      // Initialize the plugin
      $('#new_post_popup').popup();

    });
  </script>
  </div>
</body>
</html>