<?php require "Connect2.php";

if(isset($_POST['loginid'])){

    $id = $_POST['loginid'];

    $stmt = $db->getsql()->prepare("SELECT * from User where uid = ?");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        $uid = $result["uid"];

       // echo json_encode($result);
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

        <div class="dialog" style="width: 600px;">

            <p class="pagename">정보 수정</p>

            <form class="useredit" id="useredit" action="usereditproc.php" method="POST">

                <p class="label">아이디(수정 불가)</p>    
                <input id="inp_txt" name="inp_id" value="<?php echo $result['uid']; ?>" readonly>

                <p class="label">이모지 아이콘(1개만 넣으세요!)</p>
                <input class="inp_emo" id="inp_emo" name="inp_emo" placeholder="⭐" maxlength="2" value="<?php echo $result['emoji']; ?>" required>
                
                <p class="label">이름</p>
                <input class="inp_txt" id="inp_name" name="inp_name" placeholder="이름" maxlength="20" value="<?php echo $result['name']; ?>" required>
                
                <p class="label">인사말/자기소개</p>
                <input class="inp_txt" id="inp_greet" name="inp_greet" placeholder="인사말" maxlength="300" value="<?php echo $result['greet']; ?>" required>
                
                <input class="button" type="submit" value="수정">
            </form>
            <button class="button" onclick="submit('user.php', '<?php echo $id; ?>')">뒤로가기</button>
    
        </div>


    </body>
</html>