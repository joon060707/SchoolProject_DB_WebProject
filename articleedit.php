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

if(isset($_POST['aid'])){

    $aid = $_POST['aid'];

    $stmt_a = $db->getsql()->prepare("SELECT * from Article where aid = ?");
    $stmt_a->bind_param("s", $aid);
    if($stmt_a->execute()){

        $article = (array) $stmt_a->get_result()->fetch_assoc();
        //echo json_encode($article);
        
    
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

        <form class="write" id="write" action="articleeditproc.php" method="POST">
            
            <input style="display: none;" name="inp_id" value="<?php echo $id; ?>" readonly>
            <input style="display: none;" name="inp_aid" value="<?php echo $aid; ?>" readonly>

            <p class="label">제목</p>    
            <input id="inp_title" name="inp_title" value="<?php echo $article['title'] ?>" required>

            <p class="label">내용</p>
            <textarea class="inp_article" id="inp_article" name="inp_article" minlength="10" maxlength="5000"
                required><?php echo $article['text'] ?></textarea>

            <input class="button" type="submit" value="수정">
        </form>
        <button class="button" onclick="submit2('article.php', '<?php echo $id; ?>', '<?php echo $aid; ?>')">뒤로가기</button>

        </div>


    </body>
</html>