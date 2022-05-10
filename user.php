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


        // 방문하는 곳
        if(isset($_POST['visitid'])){

            $visit = $_POST['visitid'];
            //echo $visit;
        
            $stmt2 = $db->getsql()->prepare("SELECT * from User where uid = ?");
            $stmt2->bind_param("s", $visit);
        
            if($stmt2->execute()){
        
                $resultv = (array) $stmt2->get_result()->fetch_assoc();
            // echo json_encode($result);
        
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





    }else{
        echo '<meta http-equiv="refresh" content="0; url=index.php"></meta>'; // 자동 홈 이동
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

    </head>
    <body>

        <div style="text-align: right; padding: 20px; font-size: 25px;">
            <button class="button" onclick="submit('user.php', '<?php echo $id; ?>')">내 페이지</button>
            <button class="button" onclick="submit('write.php', '<?php echo $id; ?>')">글쓰기</button>
            <button class="button" onclick="submit('index.php', '')">로그아웃</button>
        </div>
        <div class="block notice" style="max-width: 1560px; margin:auto;">
            <b>공지사항</b>
            <p>안녕하세요</p>
        </div>

        <div class="userpage">
        <div class="row" style='width: 400px;'>
            <!-- <div class="block feed" style="height: 500px;">feed list</div> -->
            <form class="reply" style="margin: 20px 20px 0px;" id="reply" action="search.php" method="POST">
            <div style="display: flex;">
                <input class="inp_txt" style="display:none" name="loginid" placeholder="" value="<?php echo $id; ?>" readonly>
                <input class="inp_txt" style="width: 90%;" id="inp_user" name="inp_user" placeholder="유저 검색"  value="" required>
                <input class="button" type="submit" value="🔍">
            </div>
            </form>
            <div class="block friend" style="height: 300px;">friend list</div>
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
                    <?php if($id == $visitedid){ echo '<button class="button" onclick="submit(\'useredit.php\', \''; echo $id; echo '\')">편집</button>'; } ?>
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
            <div class="block favorite" style="height: 400px;">favorite list</div>
        </div>
            
            <!-- <div class="block" style="height: 600px;"></div> -->
        </div>


    </body>
</html>