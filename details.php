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
<div id="navigation">
 <?php 
 if(!isset($_SESSION['status']) || $_SESSION['status'] != 1 ) 
    echo "<a href='loginform.php'>LOG IN</a>";
 elseif(isset($_SESSION['status']) && $_SESSION['status'] == 1) 
 {
    echo "<ul>";
    echo "<li><a href='#'>Welcome " . $_SESSION['ad'] ."</a></li>";
    echo "<li><a href = 'admin.php'>Admin Panel</a></li>";
    echo "<li><a class='new_post_popup_open' href='#'>New Post</a></li>";
 
    echo "<li style='float:right;border-left: 1px solid #ececee;border-right: 0;margin-right: -55px;'><a href = 'logout.php'>Logout</a></li>";
    echo "</ul>";
 }

  ?>
</div>
 <div id="navigation">
 <ul><li>Youtube Search </li></ul>
    <form method="GET" action="youtube.php" style="position: relative;top: -13px;left: -35px;">  
      <input type="search" id="q" name="q" placeholder="Enter Search Term" style="height: 31px;">  
       <label style="font-size: 12px;">Max Results:</label> <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="20">  
      <input style="margin-top: 2px;" type="submit" value="Search">  
    </form>  
 </div>
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