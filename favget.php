<?php require "Connect2.php";?> 

<?php

if(isset($_GET['uid']) && isset($_GET['aid'])){

    $uid = $_GET['uid'];
    $aid = $_GET['aid'];

    $stmt = $db->getsql()->prepare("SELECT * from Favorite where aid = ? and uid = ?");
    $stmt->bind_param("ss", $aid, $uid);


    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        if($result!=null && $result['uid']==$uid && $result['aid']==$aid){
            echo $result['fid'];
        } else{
            echo '';
        }
 
    } else{
        echo '';
    }

    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}else{
    echo '';
}

?>
