<?php
    session_start(); // เริ่ม session
    if(isset($_GET['logout'])) {
        session_destroy(); // ทำลาย session
        // Redirect ไปยังหน้า login.html หรือหน้าอื่นที่ต้องการ
        header("Location: login.html");
        exit(); // ออกจากการประมวลผลของหน้านี้
    }
?>