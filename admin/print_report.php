<?php
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_GET["startDate"]) && isset($_GET["endDate"])) {
        $startDate = mysqli_real_escape_string($cx, $_GET["startDate"]);
        $endDate = mysqli_real_escape_string($cx, $_GET["endDate"]);
    
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Confirm Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container1 {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header_invoice th, .header_invoice td {
            border: none;
            background-color: transparent;
        }

        .header_invoice table {
            width: 100%;
        }

        .header_invoice .left-col {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .header_invoice .right-col {
            width: 50%;
            vertical-align: top;
            padding-left: 20px;
        }

        .header_invoice .address {
            font-size: 14px;
            line-height: 1.6;
        }

        .total-row td:last-child {
            font-weight: bold;
        }
        .button_con {
            display: flex;
            justify-content: center;
        }
        .button_con {
            text-align: center; /* จัดให้เนื้อหาอยู่ตรงกลาง */
            margin-top: 20px; /* ขอบด้านบน */
        }

        .button_con a {
            text-decoration: none; /* ลบ underline ของลิงค์ */
        }

        .button_con button {
            padding: 10px 20px; /* ปรับขนาดของปุ่ม */
            margin: 0 10px; /* ระยะห่างระหว่างปุ่ม */
            border: none; /* ลบเส้นขอบของปุ่ม */
            border-radius: 5px; /* ทำให้มีเส้นขอบเวียนมน */
            background-color: #4CAF50; /* สีพื้นหลังของปุ่ม */
            color: white; /* สีของตัวอักษรภายในปุ่ม */
            font-size: 16px; /* ขนาดตัวอักษร */
            cursor: pointer; /* ทำให้เป็นเคอร์เซอร์เมื่อชี้ */
            transition: background-color 0.3s; /* เพื่อทำให้เปลี่ยนสีพื้นหลังเมื่อโฮเวอร์ */
        }

        .button_con button:hover {
            background-color: #45a049; /* เปลี่ยนสีพื้นหลังเมื่อโฮเวอร์ */
        }
    </style>
</head>
<body>
    <?php

    $sql_purchase = "SELECT
    ph.PurchaseNo AS PurchaseNo,
    ph.Status AS `Status`,
    ph.Date AS `Date`,
    ph.Total AS `Total`,
    c.CustName AS CustName
    FROM purchase AS ph
    INNER JOIN customer AS c ON c.CustNo = ph.CustNo
    WHERE `ph`.`Date` BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";

    $result_purchase = mysqli_query($cx, $sql_purchase);

?>
    <div class="container1">
        <h1>รายงานยอดขาย</h1>
        <p>ประจำวันที่  <?php echo $startDate; ?> ถึง <?php echo $endDate; ?> </p>
        <table>
            <tr>
                <th>ลำดับที่</th>
                <th>เลขที่ใบสั่งซื้อ</th>
                <th>ชื่อลูกค้า</th>
                <th>ราคารวม</th>
                <th>ต้นทุน</th>
                <th>กำไร</th>
                <th>วันที่สั่งซื้อ</th>
                <th>สถานะใบสั่งซื้อ</th>
            </tr>
            <?php
                $i = 0;
                $sumTotal = 0;
                $sumTotalProfit = 0;
                $sumTotalCost = 0;
                while ($row = mysqli_fetch_assoc($result_purchase)) {
                    $i = $i+1;
                    $purchaseNo =  $row['PurchaseNo'];
                    $totalCost = 0; // รีเซ็ตค่าต้นทุนทุกครั้งที่วนลูปใหม่
                    $totalProfit = 0; // รีเซ็ตค่ากำไรทุกครั้งที่วนลูปใหม่
                    $sql_order = "SELECT
                        o.OrderNo AS OrderNo,
                        o.ProductNo AS ProductNo,
                        p.PricePerUnit AS PricePerUnit,
                        p.Cost AS Cost,
                        o.ProductQty AS ProductQty
                        FROM orders AS o
                        INNER JOIN product AS p ON o.ProductNo = p.ProductNo
                        WHERE o.PurchaseNo = $purchaseNo";

                    $result_order = mysqli_query($cx, $sql_order);
                    // คำนวณต้นทุนและกำไร
                    while($row_order = mysqli_fetch_assoc($result_order)){
                        $pricePerUnit = $row_order['PricePerUnit']; // ราคาขายต่อหน่วย
                        $cost = $row_order['Cost']; // ราคาทุนต่อหน่วย
                        $totalCost += $cost * $row_order['ProductQty']; // คำนวณต้นทุนรวม
                        // คำนวณกำไรจากการขายสินค้านี้
                        $profit = ($pricePerUnit - $cost) * $row_order['ProductQty'];
                        $totalProfit += $profit; // คำนวณกำไรรวม
                    }
                    $sumTotal += $row['Total'];
                    $sumTotalCost  += $totalCost;
                    $sumTotalProfit += $totalProfit;
                    
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['PurchaseNo']; ?></td>
                    <td><?php echo $row['CustName']; ?></td>
                    <td><?php echo $row['Total']; ?></td>
                    <td><?php echo $totalCost; ?></td>
                    <td><?php echo $totalProfit; ?></td>
                    <td><?php echo $row['Date']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                </tr>
                <?php
                    }
                    mysqli_close($cx);
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td>รวม</td>
                    <td><?php echo $sumTotal; ?></td>
                    <td><?php echo $sumTotalCost; ?></td>
                    <td><?php echo $sumTotalProfit; ?></td>
                    <td></td>
                    <td></td>
                </tr>
        </table>
        <br></br>

        <p>ยอดขายทั้งหมด <?php echo $i; ?> รายการ</p>            
        <p>ยอดขายรวม <?php echo $sumTotal; ?> บาท</p>
        <p>ต้นทุนรวม <?php echo $sumTotalCost; ?> บาท</p>
        <p>กำไรรวม <?php echo $sumTotalProfit; ?> บาท</p>
        

    </div>
            
    <div class="button_con">
        <a href="pdf_report.php?startDate=<?php echo $startDate; ?>&endDate=<?php echo $endDate; ?>">
            <button>พิมพ์รายงานยอดขาย</button>
        </a>

    </div>
</body>
</html>

