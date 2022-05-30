<?php require "Connect2.php";?> 

<?php

if(isset($_POST['inp_aid']) && isset($_POST['inp_id']) && isset($_POST['inp_title']) && isset($_POST['inp_article'])){

    $id = $_POST['inp_id'];
    $aid = $_POST['inp_aid'];
    $title = $_POST['inp_title'];
    $txt = $_POST['inp_article'];
    $view = 0;
    $show = 1;

    $stmt = $db->getsql()->prepare("UPDATE Article set title = ?, text = ? where aid = ?");
    $stmt->bind_param("sss", $title, $txt, $aid);
    $stmt->execute();
}

echo "<html> <head> <script src='submit.js'></script> <script> window.onload = function(){ submit('user.php', '";
if($id!=null) echo $id;
echo "'); } </script> </head> </html>"; 

?>
