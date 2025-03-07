<?php
session_start();
$cx = mysqli_connect("localhost", "root", "", "app");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productNo']) && isset($_POST['qty'])) {
    
    $custId = $_SESSION['custId'];
    $productNo = $_POST['productNo'];
    $quantity = $_POST['qty'];

    // อัปเดตจำนวนสินค้าใน shoppingcart ในฐานข้อมูล
    for ($i = 0; $i < count($productNo); $i++) {
        $updateQuery = "UPDATE shoppingcart SET ProductQty = '{$quantity[$i]}' WHERE ProductNo = '{$productNo[$i]}'";
        $updateResult = mysqli_query($cx, $updateQuery);
    }
    

    if ($updateResult) {
        // สร้างสตริงของ productNo และ qty ที่จะส่ง
        $productNoString = implode("&productNo[]=", $productNo);
        $qtyString = implode("&qty[]=", $quantity);

        // เชื่อมต่อข้อมูลทั้งสองเข้าด้วยกัน
        $urlParameters = "&productNo[]=$productNoString&qty[]=$qtyString";

        // Redirect ไปยัง test_make.php พร้อมส่งค่าข้อมูลที่ต้องการ
        header("Location: test_make.php?custno=$custId$urlParameters");
        exit; // จบการทำงานของสคริปต์หลังจากการ redirect
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}

mysqli_close($cx);
?>