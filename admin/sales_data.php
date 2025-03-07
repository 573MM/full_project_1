<?php
    $cx = mysqli_connect("localhost", "root", "", "app");

    // คำสั่ง SQL เพื่อดึงข้อมูลยอดขายในแต่ละวัน
    $sql = "SELECT Date(`Date`) AS SaleDate, SUM(Total) AS TotalSales FROM purchase GROUP BY Date(`Date`)";

    $result = mysqli_query($cx, $sql);

    // กำหนด header ให้รู้ว่าข้อมูลที่ส่งกลับเป็น JSON
    header('Content-Type: application/json');

    // ดึงข้อมูลเป็นรูปแบบ JSON และส่งกลับ
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    mysqli_close($cx);
?>