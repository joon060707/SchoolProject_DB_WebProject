<?php require "Connect2.php";?> 

<?php

if(isset($_POST['inp_id']) && isset($_POST['inp_emo']) && isset($_POST['inp_name']) && isset($_POST['inp_greet'])){

    $id = $_POST['inp_id'];
    $name = $_POST['inp_name'];
    $greet = $_POST['inp_greet'];
    $emo = $_POST['inp_emo'];

    $stmt = $db->getsql()->prepare("UPDATE User set name = ?, greet = ?, emoji = ? where uid = ?");
    $stmt->bind_param("ssss", $name, $greet, $emo, $id);
    $stmt->execute();

}

echo "<html> <head> <script src='submit.js'></script> <script> window.onload = function(){ submit('user.php', '";
if($id!=null) echo $id;
echo "'); } </script> </head> </html>"; 

?>
