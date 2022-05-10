<?php require "Connect2.php";

if(isset($_POST['loginid'])){

    $id = $_POST['loginid'];

    $stmt = $db->getsql()->prepare("SELECT * from User where uid = ?");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        $uid = $result["uid"];

        //echo json_encode($result);
    }
}
if(isset($_POST['inp_user'])){ //유저 검색창

    $name = $_POST['inp_user'];
   // echo $name;

    $stmt = $db->getsql()->prepare("SELECT * from User where uid like '%".$name."%' or name like '%".$name."%'");

    if($stmt->execute()){

        $result_user = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        //echo json_encode($result_user);
    }
}

?> 

<html>
    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>myThought</title>
        <!--  css  -->
        <link rel="stylesheet" href="user.css">

        <!--  icon  -->
        <link rel="icon" href="icon.ico">

        <script src="submit.js"></script>
        <script>


        </script>

    </head>
    <body>


        <div class="dialog" style="width: 800px; min-width: 300px">

            <p class="pagename">검색 결과</p>

            <form class="reply" style="margin: 20px 20px 0px;" id="reply" action="search.php" method="POST">
                <div style="display: flex;">
                    <input class="inp_txt" style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                    <input class="inp_txt" style="width: 90%;" id="inp_user" name="inp_user" placeholder="유저 검색"  value="<?php echo $name; ?>" required>
                    <input class="button" type="submit" value="🔍">
                </div>
            </form>

            <?php
                // 검색 결과 표시
                for($i=0; $i<sizeof($result_user); $i++){

                    echo '<div class="block_search" onclick="gouser(\'user.php\', \'';
                    echo $id;
                    echo '\', \'';
                    echo $result_user[$i]["uid"];
                    echo '\')">';
                    
                    echo '<div class="emoji2">';
                    echo $result_user[$i]['emoji'];
                    echo '</div>';

                    echo '<div style="text-align: left; margin: auto 20px; width: 100%">';
                    echo '<p style="font-size: 30px; font-weight:500;">';
                    echo $result_user[$i]["name"];
                    echo '</p>';

                    echo '<p style="font-size: 20px;"> @';
                    echo $result_user[$i]["uid"];
                    echo '</p></div></div>';

                }
            ?>

            <!-- Original
            <div class="block_search" <?php echo 'onclick="gouser(\'user.php\', \''; echo $id; echo '\', \'';echo $result_user[$i]["uid"];echo '\')"';?> >
                <div class="emoji2"> <?php echo $result_user[0]['emoji']; ?> </div>
                <div style="text-align: left; margin: auto 20px; width: 100%">
                    <p style="font-size: 30px; font-weight:500;"> <?php echo $result_user[0]["name"]; ?> </p>
                    <p style="font-size: 20px;"> @<?php echo $result_user[0]["uid"]; ?> </p>
                </div>
            </div> -->


        <button class="button" onclick="submit('user.php', '<?php echo $id; ?>')">뒤로가기</button>

        </div>


    </body>
</html>