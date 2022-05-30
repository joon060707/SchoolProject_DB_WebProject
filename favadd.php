<?php require "Connect2.php";?> 

<?php

if(isset($_GET['uid']) && isset($_GET['aid'])){

    $uid = $_GET['uid'];
    $aid = $_GET['aid'];

    $stmt = $db->getsql()->prepare("INSERT into Favorite values(?, ?, ?)");
    $fid = uniqid("f", true);
    $stmt->bind_param("sss", $fid, $aid, $uid);


    if($stmt->execute()){
        echo $fid;
    } else{
        echo 'Failure';
    }

    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}else{
    echo 'Failure';
}

?>
