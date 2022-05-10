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
            function login() {
                var id = document.getElementById("loginid").value;
                var pw = document.getElementById("loginpw").value;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var result = this.responseText.trim();
                        //document.getElementById("logintest").innerText = result;

                        if(result=="일치"){
                            document.getElementById("loginerr").style.display = "none";
                            //var form = document.getElementById("loginform");
                            var form = document.forms.namedItem("loginform");
                            if(form.requestSubmit) form.requestSubmit();
                            else form.submit();
                        }else{
                            document.getElementById("loginerr").style.display = "block";
                        }
                    }
                };
                xmlhttp.open("GET", "login.php?loginid=" + id +"&loginpw="+pw, true);
                xmlhttp.send();
            }
        </script>

    </head>
    <body>

        <div class="main">

            <div class="loginerror" id="loginerr" style="display: none;">
            <p class="msg">아이디가 없거나 비밀번호가 일치하지 않습니다. 다시 시도해 주세요.</p>
            </div>

            <form class="login" id="loginform" action="user.php" method="POST">
                <p class="logo" id="logintest">My thought</p>
                <input class="login_in" id="loginid" name="loginid" placeholder="아이디" required>
                <input class="login_in" id="loginpw" name="loginpw" placeholder="비밀번호" type="password" required>
            </form>
                <button class="button" onclick="login()">로그인</button>

            <a href="signup.php"><p style="width: 200px; margin: auto; font-size:20px;">가입</p></a>

    
        </div>


    </body>
</html>