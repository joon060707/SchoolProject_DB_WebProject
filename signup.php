<?php require "Connect2.php";?> 

<html>
    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>myThought</title>
        <!--  css  -->
        <link rel="stylesheet" href="index.css">

        <!--  icon  -->
        <link rel="icon" href="icon.ico">

        <script>
            function signin() {
                var id = document.getElementById("id").value;
                if(id==""){
                    alert("아이디를 입력하세요.");
                    return;
                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        if(result=="존재"){
                            document.getElementById("signuperr").style.display = "block";
                        }else{
                            document.getElementById("signuperr").style.display = "none";

                            if(confirm("이 아이디("+id+")는 사용 가능한 아이디입니다. 가입하겠습니까?")){
                                var form = document.getElementById("signupform");
                                form.submit();
                            }

                        }
                    }
                };
                xmlhttp.open("GET", "signupcheck.php?id=" + id, true);
                xmlhttp.send();
            }
        </script>

    </head>
    <body>

        <div class="main">

            <div class="loginerror" id="signuperr" style="display: none;">
            <p class="msg">이미 존재하는 아이디입니다.</p>
            </div>

            <form class="login" id="signupform" action="signupprocess.php" method="POST">
                <p class="logo">회원 가입</p>

                <p>아이디</p>
                <input class="login_in" id="id" name="id" placeholder="아이디" required>

                <p>비밀번호</p>
                <input class="login_in" id="pw" name="pw" placeholder="비밀번호" type="password" required>

                <p>사람들에게 표시할 이름: 프로필에 표시됩니다</p>
                <input class="login_in" id="name" name="name" placeholder="이름" required>

                <p>자기소개 또는 인사말: 프로필에 표시됩니다</p>
                <input class="login_in" id="greet" name="greet" placeholder="인사말" value="안녕하세요~">

            </form>
                <button class="button" onclick="signin()">가입</button>
               

            <a href="index.php"><p style="width: 200px; margin: auto; font-size:20px;">뒤로</p></a>

            
        </div>

        <!-- <div class="test" style="display: block;">
            <p class="title">테스트</p>
            <?php        
            // $stmt = $db->getsql()->prepare("SELECT * from User");
            // if($stmt->execute()){

            //     $result = (array) $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            //     echo json_encode($result);
            //     echo $result[0]['greet'];
            //     echo $result[0]['greet'];
            // }
            // for($i=0; $i<10; $i++){
            //     echo('<p class="title">테스트<br/></p>');
            //     echo("<p class='title'>테스트2<br/></p>");
            // }
            ?>
        </div> -->

    </body>
</html>