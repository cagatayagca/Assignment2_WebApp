<?php
ob_start(); 
session_start();
$db = @new mysqli('localhost', 'root', '', 'blog');
if ($db->connect_errno)  die('ERROR' . $db->connect_error);


$db->set_charset("utf8");

if(isset($_SESSION['uye'])){
  if($_SESSION['uye'] == 2){
     header('Location: index.php');
  }
}else{
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
 <head>

  <title> My Blog </title>
  <meta charset="utf-8" />
  
 <link href="css/style.css" type="text/css" rel="stylesheet" />
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
if(isset($_POST['ekle']) && !empty($_POST['ekle'])){
  $sql ="INSERT INTO blog(baslik,yazi) VALUES(?,?)";
}
else if(isset($_POST['guncelle'])){
  $sql ="UPDATE blog SET baslik=?,yazi=? WHERE blog_id=?";

}else if(isset($_GET['sil'])){
  $sql ="DELETE FROM blog WHERE blog_id=?";

}else if(isset($_GET['yorum_sil'])){
  $sql ="DELETE FROM yorum WHERE yorum_id=?";
  $_GET['sil']= $_GET['yorum_sil'];


}
if(isset($_POST['ekle']) || isset($_POST['guncelle']) ){

 
  $stmt = $db->prepare($sql);
  if ($stmt === false) die('ERROR'. $db->error);

  if($_POST['ekle'])
  $stmt->bind_param("ss", $_POST['baslik'],$_POST['yazi']);

  if($_POST['guncelle'])
  $stmt->bind_param("ssi", $_POST['baslik'],$_POST['yazi'],$_POST['blog_id']);

  $stmt->execute();
    if($db->affected_rows < 1){
     die('COULDNT ADD');
  }
  $stmt->close();
}else if(isset($_GET['sil']) || isset($_GET['yorum_sil'])){


  $stmt = $db->prepare($sql);
  if ($stmt === false) die('ERROR'. $db->error);

 
  $stmt->bind_param("i", $_GET['sil']);


  $stmt->execute();
  if($db->affected_rows < 1){
     die('Post wasnt deleted');
  }
   if(isset($_GET['yorum_sil']))
  header('Location: details.php?id='.$_GET['id']);
  $stmt->close();
}

if(isset($_GET['guncelle'])){

 
  $stmt  = $db->prepare("SELECT * FROM blog WHERE blog_id=?");

  $stmt->bind_param("i", $_GET['guncelle']);

  $stmt->execute();

  $sonuc = $stmt->get_result();

  $row = $sonuc->fetch_array();

  echo '<h3 style="text-align:center">UPDATE</h3>
  <form method="post" action="admin.php">
  <input type="hidden" name="blog_id" value="'.$row['blog_id'].'"/>
  <dl>
  <dt>Title:</dt> 
  <dd><input type="text" name="baslik" value="'.$row['baslik'].'" /></dd>
  <dt>Text:</dt>
  <dd><textarea rows="5" cols="30" name="yazi">'.$row['yazi'].'</textarea></dd>
    </dl>
  <input style="margin-left:170px" type="submit" name="guncelle" value="Kaydet" />
 
 
  </form>';

  $stmt->close();
}else{
  echo '<h3 style="text-align:center">NEW</h3>
   <form method="post" action="admin.php">
   <dl>
  <dt> Title:</dt> 
  <dd><input type="text" name="baslik" /></dd>
  <dt>Text:</dt>
  <dd><textarea rows="5" cols="30" name="yazi"></textarea></dd>
   
  </dl>
<input style="margin-left:170px"  type="submit" name="ekle" value="Submit" />
  </form>';
}

$blog  = $db->prepare("SELECT * FROM blog");


$blog->execute();


$blog_sonuc = $blog->get_result();


echo '<hr /><table  cellspacing="0">';
while ($row = $blog_sonuc->fetch_array()) {
  echo "<tr>
  <td>{$row['baslik']}</td><td>
  <a href='?guncelle={$row['blog_id']}'>UPDATE</a>
  <a href='?sil={$row['blog_id']}' onclick=\"return confirm('DELETE?')\">DELETE</a>
  </td>
  </tr>\n";
}
echo '</table>';

$blog->close();

$db->close();

?>
</div>
</body>
</html>
<?php ob_end_flush(); ?>