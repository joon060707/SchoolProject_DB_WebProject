<?php require "Connect2.php";?> 

<?php

if(isset($_POST['inp_id']) && isset($_POST['inp_title']) && isset($_POST['inp_article'])){

    $id = $_POST['inp_id'];
    $title = $_POST['inp_title'];
    $txt = $_POST['inp_article'];
    $view = 0;
    $show = 1;

    $stmt = $db->getsql()->prepare("INSERT into Article values( ?, ?, ?, ?, ?, date_add(now(), interval 9 hour), ?)");
    $stmt->bind_param("ssssii", uniqid("a", true), $id, $title, $txt, $view, $show);
    $stmt->execute();
}

echo "<html> <head> <script src='submit.js'></script> <script> window.onload = function(){ submit('user.php', '";
if($id!=null) echo $id;
echo "'); } </script> </head> </html>"; 

?>
