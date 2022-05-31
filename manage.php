<?php require "Connect2.php";?> 

<?php

if(isset($_POST['inp_notice'])){
    $stmt_n = $db->getsql()->prepare("UPDATE Manager set notice = ?, date = date_add(now(), interval 9 hour)");
    $stmt_n->bind_param("s", $_POST['inp_notice']);
    if($stmt_n->execute()){
        $notice = true;
    }
}

if(isset($_POST['aid'])){
    $aid = $_POST['aid'];
    $stmt = $db->getsql()->prepare("DELETE from Article where aid = ?");
    $stmt->bind_param("s", $aid);
    if($stmt->execute()){
        $article = true;
    }
}

if(isset($_POST['uid'])){
    $uid = $_POST['uid'];
    $stmt = $db->getsql()->prepare("DELETE from User where uid = ?");
    $stmt->bind_param("s", $uid);
    if($stmt->execute()){
        $user = true;
    }
}



if(isset($_POST['loginid']) && isset($_POST['loginpw'])){

    $id = $_POST['loginid'];
    $pw = $_POST['loginpw'];

    $stmt = $db->getsql()->prepare("SELECT * from Manager");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($result!=null && $result[0]['manpw']==$pw && $result[0]['manid']==$id){

            //echo json_encode($result);
            // 글 목록

            $stmt_a = $db->getsql()->prepare("SELECT * from Article order by Article.date desc");

            if($stmt_a->execute()){
                $result_a = (array) $stmt_a->get_result()->fetch_all(MYSQLI_ASSOC);
            }

            for($i=0; $i<sizeof($result_a); $i++){
                // 추천수
                $stmt_c = $db->getsql()->prepare("SELECT COUNT(*) from Favorite where aid = ?");
                $stmt_c->bind_param("s", $result_a[$i]['aid']);

                if($stmt_c->execute()){
                    $result_a[$i]['like'] = $stmt_c->get_result()->fetch_assoc()['COUNT(*)'];
                }

                // 댓글수
                $stmt_r = $db->getsql()->prepare("SELECT COUNT(*) from Reply where aid = ?");
                $stmt_r->bind_param("s", $result_a[$i]['aid']);

                if($stmt_r->execute()){
                    $result_a[$i]['reply'] = $stmt_r->get_result()->fetch_assoc()['COUNT(*)'];
                }

                // 유저 이름
                $stmt_n = $db->getsql()->prepare("SELECT name from User where uid = ?");
                $stmt_n->bind_param("s", $result_a[$i]['uid']);

                if($stmt_n->execute()){
                    $result_a[$i]['name'] = $stmt_n->get_result()->fetch_assoc()['name'];
                }

            }

            // 유저 목록

            $stmt_u = $db->getsql()->prepare("SELECT * from User order by User.since desc");

            if($stmt_u->execute()){
                $result_u = (array) $stmt_u->get_result()->fetch_all(MYSQLI_ASSOC);
            }

            for($i=0; $i<sizeof($result_u); $i++){

                $stmt_c2 = $db->getsql()->prepare("SELECT COUNT(*) from Article where uid = ?");
                $stmt_c2->bind_param("s", $result_u[$i]['uid']);

                if($stmt_c2->execute()){
                    $result_u[$i]['count'] = $stmt_c2->get_result()->fetch_assoc()['COUNT(*)'];
                }

            }




            
        }else{
            echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
        }

    }else{
        echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
    }
    
    

}else{
    echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
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

            var notice = <?php if($notice) echo 'true'; else echo 'false'; ?>;
            var article = <?php if($article) echo 'true'; else echo 'false'; ?>;
            var user = <?php if($user) echo 'true'; else echo 'false'; ?>;

            window.onload = function(){
                if(notice){
                    alert('수정 완료');
                    return;
                }
                if(article){
                    alert('글 삭제 완료');
                    return;
                }
                if(user){
                    alert('유저 삭제 완료');
                    return;
                }

            }

            function popup(str){
                alert(str);
                nl2br("");
            }


        </script>

    </head>

    <body>
        <div style="text-align: right; padding: 20px; font-size: 25px;">
            <button class="button" style="margin: 0px" onclick="submit('index.php', '')">로그아웃</button>
        </div>
        
        <div class="block">
            <p style="font-size: 22px; font-weight: 500;">공지사항 수정</p>
            <form class="noticeedit" id="noticeedit" action="manage.php" method="POST">
                <input style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                <input style="display:none" name="loginpw" placeholder="" value="<?php echo $pw; ?>" readonly>
                <input class="inp_txt" id="inp_notice" name="inp_notice" placeholder="공지" maxlength="500" value="<?php echo $result[0]['notice']; ?>" required>
                <input class="button" style="margin: 0px" type="submit" value="수정">
            </form>
        </div>

        <div class="block">
            <p style="font-size: 22px; font-weight: 500;">글 관리</p>
            <!-- <?php echo json_encode($result_a); ?> -->

            <table class="articlelist">
                    <tr style="border-bottom: 2px solid black;">
                        <td style="width: 20%">제목</td>
                        <td>내용</td>
                        <td style="text-align: center;">조회수</td>
                        <td style="text-align: center;">댓글수</td>
                        <td style="text-align: center;">추천수</td>
                        <td style="width: 10%">작성자</td>
                        <td style="width: 20%">날짜</td>
                        <td style="text-align: right;">삭제</td>
                    </tr>

                    <?php
                    for($i=0; $i<sizeof($result_a); $i++){
                    ?>
                    <tr>
                    <td style="font-size: 16px;"><?php echo $result_a[$i]['title']; ?> </td>
                    <td><button class="button" style="margin: 0px" onclick="alert(`<?php echo $result_a[$i]['text']?>`)">보기</button></td>
                    <td style="font-size: 16px; text-align: center;"><?php echo $result_a[$i]['view']; ?> </td>
                    <td style="font-size: 16px; text-align: center;"><?php echo $result_a[$i]['reply']; ?> </td>
                    <td style="font-size: 16px; text-align: center;"><?php echo $result_a[$i]['like']; ?> </td>
                    <td style="font-size: 16px;"><?php echo $result_a[$i]['name']; ?> </td>
                    <td style="font-size: 16px;"><?php echo $result_a[$i]['date']; ?> </td>
                    <td style="text-align: right;">
                        <form class="articledel" id="articledel" action="manage.php" onsubmit="return confirm('정말 삭제하시겠습니까?')" method="POST">
                            <input style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                            <input style="display:none" name="loginpw" placeholder="" value="<?php echo $pw; ?>" readonly>
                            <input style="display:none" name="aid" placeholder="" value="<?php echo $result_a[$i]['aid']; ?>" readonly>
                            <input class="button" style="margin: 0px" type="submit" value="삭제">
                        </form>
                    </td>
                    </tr>
                    <?php
                    }
                    ?>


            </table>

        </div>

        <div class="block">
            <p style="font-size: 22px; font-weight: 500;">유저 관리</p>
            <!-- <?php echo json_encode($result_u); ?> -->

            <table class="articlelist">
                    <tr style="border-bottom: 2px solid black;">
                        <td style="width: 15%">유저 id</td>
                        <td style="width: 15%">유저 이름</td>
                        <td style="width: 6%; text-align: center;">글 개수</td>
                        <td style="width: 20%">가입일</td>
                        <td style="width: 30%">인사말</td>
                        <td style="width: 6%; text-align: center;">이모지</td>
                        <td style="text-align: right;">삭제</td>
                    </tr>

                    <?php
                    for($i=0; $i<sizeof($result_u); $i++){
                    ?>
                    <tr>
                    <td style="font-size: 16px;"><?php echo $result_u[$i]['uid']; ?> </td>
                    <td style="font-size: 16px;"><?php echo $result_u[$i]['name']; ?> </td>
                    <td style="font-size: 16px; text-align: center;"><?php echo $result_u[$i]['count']; ?> </td>
                    <td style="font-size: 16px;"><?php echo $result_u[$i]['since']; ?> </td>
                    <td style="font-size: 16px;"><?php echo $result_u[$i]['greet']; ?> </td>
                    <td style="font-size: 35px; text-align: center;"><?php echo $result_u[$i]['emoji']; ?> </td>
                    <td style="text-align: right;">
                        <form class="userdel" id="userdel" action="manage.php" onsubmit="return confirm('정말 삭제하시겠습니까?')" method="POST">
                            <input style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                            <input style="display:none" name="loginpw" placeholder="" value="<?php echo $pw; ?>" readonly>
                            <input style="display:none" name="uid" placeholder="" value="<?php echo $result_u[$i]['uid']; ?>" readonly>
                            <input class="button" style="margin: 0px" type="submit" value="삭제">
                        </form>
                    </td>
                    </tr>
                    <?php
                    }
                    ?>


            </table>
        </div>


    </body>

</html>