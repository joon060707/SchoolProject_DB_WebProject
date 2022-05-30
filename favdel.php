<?php require "Connect2.php";?> 

<?php

if(isset($_GET['fid'])){

    $fid = $_GET['fid'];

    $stmt = $db->getsql()->prepare("DELETE from Favorite where fid = ?");
    $stmt->bind_param("s", $fid);


    if($stmt->execute()){
        echo 'Success';
    } else{
        echo 'Failure';
    }

    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}else{
    echo 'Failure';
}

?>
