<?php
    if(isset($_POST['a1'])) {
        $a1 = $_POST['a1'];
    }else {
        echo "No parameters received";
    }
    $conn = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($conn, "update customer set CustName='$_POST[a2]', Sex='$_POST[a3]', Address='$_POST[a4]', Tel='$_POST[a5]', UserName='$_POST[a6]' where CustNo='$a1' ");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Update data = <font color=red> '$a1' </font> is successful.";
    }

    mysqli_close($conn);
?>