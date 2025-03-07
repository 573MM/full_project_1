<?php 
    $cx = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($cx, 
    "insert into customer(CustName,UserName,Password,Sex,Address,Tel,Email) 
    values('$_POST[FnameLname]','$_POST[username]','$_POST[password]','$_POST[SelectSex]','$_POST[address]','$_POST[tel]','$_POST[email]')");

    if(mysqli_execute($stmt)){
        mysqli_close($cx);
        header("Location: login.html");
        exit();
    } else {
        echo "Error";
    }
?>