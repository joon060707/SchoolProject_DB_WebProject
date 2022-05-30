<?php require "Connect2.php";?> 

<?php

if(isset($_GET['uid']) && isset($_GET['fid'])){

    $uid = $_GET['uid'];
    $fid = $_GET['fid'];

    $stmt = $db->getsql()->prepare("SELECT * from Friend where uid = ? and frienduid = ?");
    $stmt->bind_param("ss", $uid, $fid);


    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        if($result!=null && $result['uid']==$uid && $result['frienduid']==$fid){
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
