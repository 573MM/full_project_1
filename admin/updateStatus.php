<?php
$cx = mysqli_connect("localhost", "root", "", "app");

// ตรวจสอบว่ามีข้อมูลที่จำเป็นส่งมาหรือไม่
if(isset($_POST["purchaseNo"], $_POST["custno"], $_POST["status"])) {
    // รับค่าจากฟอร์ม
    $purchaseNo = $_POST["purchaseNo"];
    $custNo = $_POST["custno"];
    $status = $_POST["status"];

    $status_purchase = 'Waiting for packing';
    // อัปเดตสถานะในฐานข้อมูล
    $update_invoice = "UPDATE invoice SET Status = '$status' WHERE PurchaseNo = '$purchaseNo'";
    $result1 = mysqli_query($cx, $update_invoice);
    $update_purchase = "UPDATE purchase SET Status = '$status_purchase' WHERE PurchaseNo = '$purchaseNo'";
    $result2 = mysqli_query($cx, $update_purchase);



    if($result1) {
        echo "อัปเดตสถานะเรียบร้อยแล้ว";
        header("Location: print_invoice.php?custno=$custNo&purchaseNo=$purchaseNo");
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . mysqli_error($cx);
        header("Location: print_invoice.php?custno=$custNo&purchaseNo=$purchaseNo");
    }
} else {
    echo "ไม่พบข้อมูลที่จำเป็น";
    header("Location: print_invoice.php");
}
?>
