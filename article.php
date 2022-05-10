<?php require "Connect2.php";

if(isset($_POST['loginid'])){

    $id = $_POST['loginid'];

    $stmt = $db->getsql()->prepare("SELECT * from User where uid = ?");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        //$uid = $result["uid"];

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
        $author = $article['uid'];


        // 조회수
        $stmt2 = $db->getsql()->prepare("UPDATE Article set view = ? where aid = ?");
        $view = $article['view']+1;

        $stmt2->bind_param("is", $view, $aid);
        $stmt2->execute();

        // 댓글 작성
        if(isset($_POST['inp_reply'])){

            $reply = $_POST['inp_reply'];
        
            $stmtr = $db->getsql()->prepare("INSERT into Reply values( ?, ?, ?, ?, date_add(now(), interval 9 hour))");
        
            $stmtr->bind_param("ssss", uniqid("r", true), $aid, $id, $reply);
            $stmtr->execute();

        }

        // 댓글 삭제
        if(isset($_POST['rem_reply'])){

            $rid = $_POST['rem_reply'];
        
            $stmtr = $db->getsql()->prepare("DELETE from Reply where rid = ?");
        
            $stmtr->bind_param("s", $rid);
            $stmtr->execute();

        }

        // 댓글 로딩
        $stmtr2 = $db->getsql()->prepare("SELECT * from Reply where aid = ?  order by Reply.date desc");
        $stmtr2->bind_param("s", $aid);
    
        if($stmtr2->execute()){
    
            $result_reply = (array) $stmtr2->get_result()->fetch_all(MYSQLI_ASSOC);
            //$uid = $result["uid"];
    
            //echo json_encode($result_reply);
        }

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

        <input style="display: none;" name="inp_id" value="<?php echo $result['uid']; ?>" readonly>

        <div class="line"></div>
        <p class="atitle"> <?php echo $article['title'];?> </p>    
        <div class="line"></div>

        <p class="adate"> 조회수: <?php echo $view;?>회 </p> 
        <p class="adate"> <?php echo $article['date'];?> </p> 

        <p class="atext"> <?php

        $txt= nl2br($article['text']);
        echo $txt;
        ?> </p> 

        <button class="button" style="margin: 0px; width: 40px; /*color: red;*/" onclick="">♥</button>

        <form class="reply" id="reply" action="article.php" method="POST">
            <div style="display: flex;">
                <input class="inp_txt" style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                <input class="inp_txt" style="display:none" name="aid" placeholder="" value="<?php echo $aid; ?>" readonly>
                <input class="inp_txt" style="width: 90%;" id="inp_reply" name="inp_reply" placeholder="댓글 입력" maxlength="500" value="" required>
                <input class="button" type="submit" value="저장">
            </div>
        </form>

        <table class="articlelist" style="margin: 20px 0px; width: auto;">
            <?php
            // 댓글 표시
 

            for($i=0; $i<sizeof($result_reply); $i++){
                echo '<tr>';
                
                echo '<td style="font-size: 18px;">';
                echo $result_reply[$i]['uid'];
                if($result_reply[$i]['uid'] == $id){
                    echo '<button class="button" style="width:60px; margin:0px;" onclick="delreply(\'article.php\', \'';
                    echo $id.'\', \'';
                    echo $aid.'\', \'';
                    echo $result_reply[$i]['rid'].'\')">';
                    echo '삭제</button>';
                }
                
                
                
                echo '</td>';

                echo '<td style="font-weight: 300;">';
                echo $result_reply[$i]['text'];
                echo '<br/><p style="color: gray;" ">작성 시간: '.$result_reply[$i]['date'].'</p>';
                echo '</td>';
                
                echo '</tr>';
            }
            ?>
        </table>

        <button class="button" onclick="<?php
        if($id == $author){
            echo "submit('user.php', '";
            echo $id;
            echo '\')';
        } else{
            echo "gouser('user.php', '";
            echo $id;
            echo '\', \'';
            echo $author;
            echo '\')';
        }
        ?>">뒤로가기</button>

        </div>


    </body>
</html>