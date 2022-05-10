<?php require "Connect2.php";?> 

<?php

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $db->getsql()->prepare("SELECT uid from User where uid = ?");
    $stmt->bind_param("s", $id);


    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($result!=null && $result[0]['uid']==$id){
            echo '존재';
        }

    }
    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}

?>
