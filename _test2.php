<?php
// echo를 불편하게 하지 않는 아주 좋은 방법
// multi html 구현 가능
$bool = true;
?>



<?php if($bool){ ?> 

<html>
    <head>
        <title>true</title>
    </head>
    <body>
        <p>bool = <?php echo $bool ?> </p>
    </body>
</html>

<?php } ?>



<?php if(!$bool){ ?> 

<html>
    <head>
        <title>false</title>
    </head>
    <body>
        <p>bool != <?php echo !$bool; echo !$bool; ?> </p>
    </body>
</html>

<?php } ?>