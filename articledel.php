<?php require "Connect2.php";?> 

<?php

if(isset($_POST['loginid']) && isset($_POST['aid'])){

    $id = $_POST['loginid'];
    $aid = $_POST['aid'];

    $stmt = $db->getsql()->prepare("DELETE from Article where aid = ?");
    $stmt->bind_param("s", $aid);
    $stmt->execute();
}

echo "<html> <head> <script src='submit.js'></script> <script> window.onload = function(){ submit('user.php', '";
if($id!=null) echo $id;
echo "'); } </script> </head> </html>"; 

?>
