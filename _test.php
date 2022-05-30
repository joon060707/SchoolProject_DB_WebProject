<?php
require "Connect.php";

$stmt = $db->getsql()->prepare("SELECT * from T");
if($stmt->execute()){

    $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode($result);


}

?> 



