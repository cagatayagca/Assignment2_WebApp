<?php
$db = @new mysqli('localhost', 'root', '1234', 'uygulama');
if ($db->connect_errno)  die('ERROR:' . $db->connect_error);


$db->set_charset("utf8");

if(isset($_POST['mesaj']) && !empty($_POST['mesaj']) && !empty($_POST['yazan'])){

  
  $yorum = $db->prepare("INSERT INTO yorum(mesaj,yazan,blog_id) VALUES(?,?,?)");
  if ($yorum === false) die('Sorgu hatası:'. $db->error);

  $mesaj = htmlspecialchars($_POST['mesaj'], ENT_QUOTES);
  $yazan = htmlspecialchars($_POST['yazan'], ENT_QUOTES);

 
  $yorum->bind_param("ssi", $mesaj, $yazan, $_POST['blog_id']);

  $yorum->execute();


  if($db->affected_rows > 0){
     header('Location: detay.php?id='.$_POST['blog_id']);
  }else{
     die('Comment didnt executed');
  }
  $yorum->close();
}else{
  echo 'You didnt write anything.';
}
$db->close();
?>