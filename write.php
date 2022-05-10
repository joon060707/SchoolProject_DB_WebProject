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

        <form class="write" id="write" action="writeproc.php" method="POST">
            
            <input style="display: none;" name="inp_id" value="<?php echo $result['uid']; ?>" readonly>

            <p class="label">제목</p>    
            <input id="inp_title" name="inp_title" required>

            <p class="label">내용</p>
            <textarea class="inp_article" id="inp_article" name="inp_article" minlength="10" maxlength="5000" required></textarea>

            <input class="button" type="submit" value="올리기">
        </form>
        <button class="button" onclick="submit('user.php', '<?php echo $id; ?>')">뒤로가기</button>

        </div>


    </body>
</html>