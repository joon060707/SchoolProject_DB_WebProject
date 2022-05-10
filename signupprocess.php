<?php require "Connect2.php";?> 

<?php

if(isset($_POST['id']) && isset($_POST['pw']) && isset($_POST['name']) && isset($_POST['greet'])){

    $id = $_POST['id'];
    $pw = $_POST['pw'];
    $name = $_POST['name'];
    $greet = $_POST['greet'];
    $emo = "⭐";

    $stmt = $db->getsql()->prepare("INSERT into User values(?, ?, ?, ?, date_add(now(), interval 9 hour), ?)");
    $stmt->bind_param("sssss", $id, $pw, $name, $greet, $emo);

    $stmt->execute();

}

echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';


?>
