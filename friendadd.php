<?php require "Connect2.php";?> 

<?php

if(isset($_GET['uid']) && isset($_GET['fid'])){

    $uid = $_GET['uid'];
    $fid = $_GET['fid'];

    $stmt = $db->getsql()->prepare("INSERT into Friend values(?, ?, ?)");
    $ffid = uniqid("f", true);
    $stmt->bind_param("sss", $ffid, $uid, $fid);


    if($stmt->execute()){
        echo $ffid;
    } else{
        echo 'Failure';
    }

    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}else{
    echo 'Failure';
}

?>
