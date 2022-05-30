<?php require "Connect2.php";


if(isset($_POST['loginid'])){ 

    $id = $_POST['loginid'];
    
    if($id==""){
        echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>';
        exit;
    }
    //echo $id;

    $stmt = $db->getsql()->prepare("SELECT * from User where uid = ?");
    $stmt->bind_param("s", $id);

    if($stmt->execute()){

        $result = (array) $stmt->get_result()->fetch_assoc();
        if($result==null){
            echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>'; // 자동 홈 이동
            exit;
        }


        // 방문하는 곳
        if(isset($_POST['visitid'])){

            $visit = $_POST['visitid'];
            //echo $visit;

        
            $stmt2 = $db->getsql()->prepare("SELECT * from User where uid = ?");
            $stmt2->bind_param("s", $visit);
        
            if($stmt2->execute()){
        
                $resultv = (array) $stmt2->get_result()->fetch_assoc();
                if($resultv==null){
                    echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>'; // 자동 홈 이동
                    exit;
                }
                //echo json_encode($result);

                // 방문한 곳이 친구인가?
                $fuid = $resultv["uid"];

                $stmtf = $db->getsql()->prepare("SELECT * from Friend where uid = ? and frienduid = ?");
                $stmtf->bind_param("ss", $id, $fuid);
               
                if($stmtf->execute()){
                    $resultf = (array) $stmtf->get_result()->fetch_assoc();

                    if($resultf!=null && $resultf['uid']==$id && $resultf['frienduid']==$fuid){
                        $fid = $resultf['fid'];
                    } else{
                        $fid = "";
                    }
             
                } else{
                    $fid = "";
                }

        
            }else{
                $resultv = $result;
            }

        }else{
            $resultv = $result;
        }

        $visitedid = $resultv["uid"];

        // 글 목록 검색  
        $stmt_a = $db->getsql()->prepare("SELECT * from Article where uid = ? order by Article.date desc");
        $stmt_a->bind_param("s", $visitedid);
        if($stmt_a->execute()){

            $result_article = (array) $stmt_a->get_result()->fetch_all(MYSQLI_ASSOC);
            //echo json_encode($result_article);
        }

        // 좋아요한 글 목록 검색
        $stmt_heart = $db->getsql()->prepare("SELECT * from Article where aid in (SELECT aid from Favorite where uid = ?) order by Article.date desc");
        $stmt_heart->bind_param("s", $visitedid);
        if($stmt_heart->execute()){

            $result_article_heart = (array) $stmt_heart->get_result()->fetch_all(MYSQLI_ASSOC);
            //echo json_encode($result_article_heart);
        }

        // 친구 전체 목록

        $stmtfa = $db->getsql()->prepare("SELECT * from User where uid in (SELECT frienduid from Friend where uid = ?)");
        $stmtfa->bind_param("s", $visitedid);
       
        if($stmtfa->execute()){
            $resultfa = (array) $stmtfa->get_result()->fetch_all(MYSQLI_ASSOC);
            //echo json_encode($resultfa);
     
        }






    }else{
        echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>'; // 자동 홈 이동
    }

    $stmtn = $db->getsql()->prepare("SELECT notice, date from Manager");
   
    if($stmtn->execute()){
        $notice = $stmtn->get_result()->fetch_assoc();
 
    }

}else{
    echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>'; // 자동 홈 이동
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

            window.onload = function(){

                var uid = ""+'<?php echo $id; ?>';
                var fuid = ""+'<?php echo $visitedid; ?>';

                document.getElementById("addfriend").style.display = "none";
                document.getElementById("delfriend").style.display = "none";

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        fid = this.responseText.trim();
                        if(fid==""){
                            document.getElementById("addfriend").style.display = "block";
                            
                        }else{
                            document.getElementById("delfriend").style.display = "block";
                        }
                    }
                };
                xmlhttp.open("GET", "friendget.php?uid=" + uid + "&fid=" + fuid, true);
                xmlhttp.send();

            }


            function addfriend() {

                var uid = ""+'<?php echo $id; ?>';
                var fuid = ""+'<?php echo $visitedid; ?>';
                

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        if(result!="Failure"){

                            fid = result;
                            //document.getElementById("test").innerText = fid;
                            
                            alert("친구가 추가되었습니다.");
                            document.getElementById("addfriend").style.display = "none";
                            document.getElementById("delfriend").style.display = "block";
                        }else{
                            alert("친구 추가 실패. 잠시 후 시도하세요.");

                        }
                    }
                };
                xmlhttp.open("GET", "friendadd.php?uid=" + uid + "&fid=" + fuid, true);
                xmlhttp.send();
            }

            function delfriend() {

                //document.getElementById("test").innerText = fid;

                if(fid==""){
                    alert("오류가 발생했습니다. 새로고침해주세요.");
                    return;
                }
                

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        if(result=="Success"){
                            alert("친구가 취소되었습니다.");
                            document.getElementById("addfriend").style.display = "block";
                            document.getElementById("delfriend").style.display = "none";
                        }else{
                            alert("친구 취소 실패. 잠시 후 시도하세요.");

                        }
                    }
                };
                xmlhttp.open("GET", "frienddel.php?fid=" + fid, true);
                xmlhttp.send();
            }
        </script>

    </head>
    <body>

        <div style="text-align: right; padding: 20px; font-size: 25px;">
            <button class="button" onclick="submit('user.php', '<?php echo $id; ?>')">내 페이지</button>
            <button class="button" onclick="submit('write.php', '<?php echo $id; ?>')">글쓰기</button>
            <button class="button" onclick="submit('index.php', '')">로그아웃</button>
        </div>
        <div class="block notice" style="max-width: 1560px; margin:auto;">
            <b>공지사항</b>(작성 시간: <?php echo $notice['date']; ?>)
            <p id="test"><?php echo $notice['notice']; ?></p>
        </div>

        <div class="userpage">
        <div class="row" style='width: 600px;'>
            <!-- <div class="block feed" style="height: 500px;">feed list</div> -->
            <form class="reply" style="margin: 20px 20px 0px;" id="reply" action="search.php" method="POST">
            <div style="display: flex;">
                <input class="inp_txt" style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                <input class="inp_txt" style="width: 90%;" id="inp_user" name="inp_user" placeholder="유저 검색(모든 유저 검색시 * 입력)"  value="" required>
                <input class="button" type="submit" value="🔍">
            </div>
            </form>
            <div class="block friend" style="min-height: 200px;">
                <p style="font-size: 26px; font-weight: 500;">친구 목록</p>
                <?php 
                    for($i=0; $i<sizeof($resultfa); $i++){

                        echo '<div class="block_search" style="padding: 10px;" onclick="gouser(\'user.php\', \'';
                        echo $id;
                        echo '\', \'';
                        echo $resultfa[$i]["uid"];
                        echo '\')">';
                        
                        echo '<div class="emoji3">';
                        echo $resultfa[$i]['emoji'];
                        echo '</div>';
        
                        echo '<div style="text-align: left; margin: auto 20px; width: 100%">';
                        echo '<p style="font-size: 25px; font-weight:500;">';
                        echo $resultfa[$i]["name"];
                        echo '</p>';
        
                        echo '<p style="font-size: 18px;"> @';
                        echo $resultfa[$i]["uid"];
                        echo '</p></div></div>';
        
                    }
                
                ?>
            </div>
        </div>
            
        <div class="row" style="width: 950px;">

            <div class="block user">


                
                <div style="display: flex">
                    <div class="emoji"> <?php echo $resultv['emoji']; ?> </div>
                    <div style="text-align: left; margin: auto 20px; width: 100%">
                        <p style="font-size: 30px; font-weight:500;"> <?php echo $resultv["name"]; ?> </p>
                        <p style="font-size: 20px;"> @<?php echo $resultv["uid"]; ?> </p>
                        <br/>
                        <p style="font-size: 18px;"> <?php echo $resultv["greet"]; ?> </p>
                    </div>
                    <div style="text-align:right; height:0px;">
                    <!-- 본인이면 편집 버튼 표시
                         타인이면 친구추가 또는 삭제 버튼 표시 -->
                    <?php if($id == $visitedid){
                        echo '<button class="button" onclick="submit(\'useredit.php\', \''; echo $id; echo '\')">편집</button>';
                        }else{
                            
                            echo '<button class="button" id="addfriend" onclick="addfriend()">친구 추가</button>';
                            echo '<button class="button" id="delfriend" onclick="delfriend()">친구 취소</button>';

                        } ?>

                    </div>
                </div>

            </div>

            <div class="block list" style="min-height: 250px;">
                <p style="font-size: 26px; font-weight: 500;">글 목록</p>
                <table class="articlelist">
                    <tr style="border-bottom: 2px solid black;">
                        <td style="width: 60%">제목</td>
                        <td style="text-align: right;">날짜</td>
                    </tr>

                    <?php
                    // 글 표시
                    for($i=0; $i<sizeof($result_article); $i++){
                        echo '<tr>';
                        
                        echo '<td style="font-size: 18px;" onclick="submit2(\'article.php\', \'';
                        echo $id;
                        echo '\', \'';
                        echo $result_article[$i]['aid'];
                        echo '\')">';

                        echo '<a href="#"><p>';
                        echo $result_article[$i]['title'];
                        echo '</p></a>';
                        echo '</td>';

                        echo '<td style="font-weight: 300; text-align: right;" onclick="submit2(\'article.php\', \'';
                        echo $id;
                        echo '\', \'';
                        echo $result_article[$i]['aid'];
                        echo '\')">';

                        echo '<a href="#"><p>';
                        echo $result_article[$i]['date'];
                        echo '</p></a>';
                        echo '</td>';
                        
                        echo '</tr>';
                    }
                    ?>

                </table>


        
            </div>


            <div class="block favorite" style="min-height: 250px;">
                <p style="font-size: 26px; font-weight: 500;">좋아요 글 목록</p>
                <table class="articlelist">
                    <tr style="border-bottom: 2px solid black;">
                        <td style="width: 60%">제목</td>
                        <td style="text-align: right;">날짜</td>
                    </tr>

                    <?php
                    // 글 표시
                    for($i=0; $i<sizeof($result_article_heart); $i++){
                        echo '<tr>';
                        
                        echo '<td style="font-size: 18px;" onclick="submit2(\'article.php\', \'';
                        echo $id;
                        echo '\', \'';
                        echo $result_article_heart[$i]['aid'];
                        echo '\')">';

                        echo '<a href="#"><p>';
                        echo $result_article_heart[$i]['title'];
                        echo '</p></a>';
                        echo '</td>';

                        echo '<td style="font-weight: 300; text-align: right;" onclick="submit2(\'article.php\', \'';
                        echo $id;
                        echo '\', \'';
                        echo $result_article_heart[$i]['aid'];
                        echo '\')">';

                        echo '<a href="#"><p>';
                        echo $result_article_heart[$i]['date'];
                        echo '</p></a>';
                        echo '</td>';
                        
                        echo '</tr>';
                    }
                    ?>

                </table>
        
            </div>

        </div>
            
            <!-- <div class="block" style="height: 600px;"></div> -->
        </div>


    </body>
</html>