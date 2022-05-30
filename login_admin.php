<?php require "Connect2.php";?> 

<?php

if(isset($_GET['loginid']) && isset($_GET['loginpw'])){

    $id = $_GET['loginid'];
    $pw = $_GET['loginpw'];

    $stmt = $db->getsql()->prepare("SELECT manid, manpw from Manager");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($result!=null && $result[0]['manpw']==$pw && $result[0]['manid']==$id){

            echo '일치';
        }

    }
    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}

?>
