<?php
    $cx = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($cx, "insert into customer(UserName,CustName,Password,Sex,Address,Tel) values('$_POST[a1]','$_POST[a2]','$_POST[a6]','$_POST[a3]','$_POST[a4]','$_POST[a5]')");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Insert data = <font color=red> '$_POST[a1]' </font> is successful.";
    }

    mysqli_close($cx);
?> 