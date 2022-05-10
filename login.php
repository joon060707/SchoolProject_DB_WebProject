<?php require "Connect2.php";?> 

<?php

if(isset($_GET['loginid']) && isset($_GET['loginpw'])){

    $id = $_GET['loginid'];
    $pw = $_GET['loginpw'];

    $stmt = $db->getsql()->prepare("SELECT pw from User where uid = ?");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($result!=null && $result[0]['pw']==$pw){
            echo '일치';
        }

    }
    
    //echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';

}

?>
