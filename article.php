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


}else{
    echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
    exit;
}

if(isset($_POST['aid'])){

    $aid = $_POST['aid'];


    $stmt_a = $db->getsql()->prepare("SELECT * from Article where aid = ?");
    $stmt_a->bind_param("s", $aid);
    if($stmt_a->execute()){

        $article = (array) $stmt_a->get_result()->fetch_assoc();
        //echo json_encode($article);

        if($article==null){   
            echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
            exit;
        }


        $author = $article['uid'];

        // 유저 이름 조회
        $stmt_name = $db->getsql()->prepare("SELECT name from User where uid = ?");
        $stmt_name->bind_param("s", $author);
        if($stmt_name->execute()){
            $authorname = $stmt_name->get_result()->fetch_assoc()['name'];
        }
        
        // 좋아요 조회
        $stmt_heart = $db->getsql()->prepare("SELECT COUNT(*) from Favorite where aid = ?");
        $stmt_heart->bind_param("s", $aid);
        if($stmt_heart->execute()){
            $heart = $stmt_heart->get_result()->fetch_assoc()["COUNT(*)"];
        } else{
            $heart = 0;
        }


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
        // select Reply.*, User.name from Reply, User where aid = 'a627a0bf58ab788.72659535' and User.uid = Reply.uid
        $stmtr2 = $db->getsql()->prepare("SELECT Reply.*, User.name from Reply, User where aid = ? and User.uid = Reply.uid order by Reply.date desc");
        $stmtr2->bind_param("s", $aid);
    
        if($stmtr2->execute()){
    
            $result_reply = (array) $stmtr2->get_result()->fetch_all(MYSQLI_ASSOC);
            for($i = 0; $i < sizeof($result_reply); $i++){

            }
            //$uid = $result["uid"];
    
            //echo json_encode($result_reply);
        }

    }else{
        echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
        exit;
    }

}else{
    echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
    exit;
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

            var fid = "";
            var heart = parseInt('<?php echo $heart; ?>');

            window.onload = function(){

                var id = '<?php echo $id; ?>'; // 로그인한 id(작성자)
                var aid = '<?php echo $aid; ?>'; // 글 id

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        fid = this.responseText.trim();
                        if(fid==""){
                            document.getElementById("fav").style.color = 'black';
                            document.getElementById("fav").onclick = favadd;
                            
                        }else{
                            document.getElementById("fav").style.color = 'red';
                            document.getElementById("fav").onclick = favdel;
                        }
                    }
                };
                xmlhttp.open("GET", "favget.php?uid=" + id + "&aid=" + aid, true);
                xmlhttp.send();
            }

            function favadd(){

                var authoruid = '<?php echo $author; ?>'; 
                var id = '<?php echo $id; ?>'; // 로그인한 id(작성자)
                var aid = '<?php echo $aid; ?>'; // 글 id

                if(authoruid==id){
                    alert("자신의 글에 '좋아요'할 수 없습니다!");
                    return;
                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        if(result!="Failure"){

                            fid = result;

                            document.getElementById("fav").style.color = 'red';
                            document.getElementById("fav").onclick = favdel;
                            console.log('favadd');
                            alert("좋아요!");
                    
                            heart++;
                            document.getElementById("favcount").innerText = '♥: '+heart;

                        }else{
                            alert("처리 오류. 잠시 후 시도하세요.");
                        }
                    }
                };

                xmlhttp.open("GET", "favadd.php?uid=" + id + "&aid=" + aid, true);
                xmlhttp.send();


            }

            function favdel(){

                if(fid==""){
                    alert("오류가 발생했습니다. 새로고침해주세요.");
                    return;
                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        if(result=="Success"){

                            document.getElementById("fav").style.color = 'black';
                            document.getElementById("fav").onclick = favadd;
                            console.log('favdel');
                            alert("좋아요가 취소되었습니다.");

                            heart--;
                            document.getElementById("favcount").innerText = '♥: '+heart;



                        }else{
                            alert("처리 오류. 잠시 후 시도하세요.");
                        }
                    }
                };

                xmlhttp.open("GET", "favdel.php?fid=" + fid, true);
                xmlhttp.send();


            }       

            function del(){
                if(confirm("정말 글을 삭제하시겠습니까? 삭제하신 글은 복구할 수 없습니다!"))
                    submit2('articledel.php', '<?php echo $id ?>', '<?php echo $aid ?>');
            }


        </script>

    </head>
    <body>

        <div class="dialog" style="width: 800px; min-width: 300px">

        <input style="display: none;" name="inp_id" value="<?php echo $result['uid']; ?>" readonly>

        <div class="line"></div>
        <p class="atitle"> <?php echo $article['title'];?> </p>    
        <div class="line"></div>

        <p class="adate" style="text-align: center"> by <?php echo $authorname;?> </p> 
        <p class="adate"> <?php echo $article['date'];?> </p> 
        <p class="adate"> 조회수: <?php echo $view;?>회 </p> 
        <p class="adate" id="favcount" style="color: red"> ♥: <?php echo $heart;?> </p> 

        <p class="atext"> <?php

        $txt= nl2br($article['text']);
        echo $txt;
        ?> </p> 

        <button class="button" id='fav' style="margin: 0px; width: 40px; /*color: red;*/" onclick="">♥</button>

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
                echo $result_reply[$i]['name'];
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

        <?php if($id == $author){ ?>
        <button class="button" onclick="submit2('articleedit.php', '<?php echo $id ?>', '<?php echo $aid ?>')">수정</button>
        <button class="button" onclick="del()">삭제</button>
        <?php } ?>

        <button class="button" onclick="<?php
            echo "submit('user.php', '";
            echo $id;
            echo '\')';
        ?>">내 홈으로</button>

        
        <button class="button" style="width: auto;" onclick="<?php
            echo "gouser('user.php', '";
            echo $id;
            echo '\', \'';
            echo $author;
            echo '\')';
        // if($id == $author){
        //     echo "submit('user.php', '";
        //     echo $id;
        //     echo '\')';
        // } else{
        //     echo "gouser('user.php', '";
        //     echo $id;
        //     echo '\', \'';
        //     echo $author;
        //     echo '\')';
        // }
        ?>">글쓴이 홈으로</button>



        </div>


    </body>
</html>