<?php
    $cx = mysqli_connect("localhost","root","","app");

    
    
    if(isset($_POST['search'])){
        $search_input = $_POST['search'];
        $query = "SELECT * FROM `purchase` WHERE LOWER(CONVERT(PurchaseNo, CHAR)) LIKE LOWER('%$search_input%') OR LOWER(CONVERT(CustNo, CHAR)) LIKE LOWER('%$search_input%') OR LOWER(Status) LIKE LOWER('%$search_input%') ORDER BY PurchaseNo";

    }else{
        $query = "SELECT * FROM `purchase` ORDER BY PurchaseNo DESC";
    }
    
    $result = mysqli_query($cx, $query);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="index.css">
    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <style>
        .container_in{
            width: 80%;
        }
        .img_con2 {
            max-width: 20px;
            height: 20px;
        }
        .img_con{
            max-width: 15px;
            height: auto;
        }
        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top > * {
            padding: 10px; /* เปลี่ยนค่า padding ตามที่คุณต้องการ */
        }
        .report{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .report p{
            font-size: 15px;
            color: #2c2c2c;
        }
        button[type="button"] {
            margin: auto;
            padding: 10px 15px;
            background-color: #00004d;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .insert_button{
            display: flex;
        }
        .insert_button img{
            margin: 16px;
        }
        .search{
            display: flex;
            align-items: center;
            text-align: center;
        }

        .search p {
            font-size: 18px;
            margin: 0;
            color: #333333;
            font-weight: bold;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            margin-right: 5px;
        }

        button[type="submit"] {
            margin: auto;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .navbar_logo img{
            margin-top: 1rem;
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
<div class='container'>
    <?php include 'navbarL.html'; ?>
    <div class='body_container'>
        <h2>Order Data</h2>
        <div class='content'>
            <div class="top">
                <div class="date">
                    <?php
                        date_default_timezone_set("Asia/Bangkok");
                    ?>
                    <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
                </div>
                <div class="search">
                    <p>ค้นหา  </p>
                    <form method="post" action="manage_order.php">
                        <input type="text" name="search">
                        <button type="submit">
                            <img  class="img_con2" src="img/search.png" alt="ค้นหา">
                        </button>
                    </form>
                </div>
                
            </div>
            <div class="report">
                <div class="date1">
                    <input type="text" id="datepicker1" placeholder="Select date">
                    <p id="selectedDate1">จากวันที่ : -</p>
                </div>
                <div class="date2">
                    <input type="text" id="datepicker2" placeholder="Select date">
                    <p id="selectedDate2">ถึง : -</p>
                </div>
                <div>
                    <form id="reportForm" method="get" action="print_report.php">
                        <!-- Input hidden สำหรับเก็บค่า dateStr -->
                        <input type="hidden" name="startDate" id="startDateInput" value="">
                        <input type="hidden" name="endDate" id="endDateInput" value="">
                        <button type="button" onclick="makeReport()">Make Report</button>
                    </form>
                    <br>
                </div>

                <script>
                    function makeReport() {
                        // ดึงค่า dateStr จาก #datepicker1 และ #datepicker2
                        var startDate = document.getElementById("datepicker1").value;
                        var endDate = document.getElementById("datepicker2").value;

                        // กำหนดค่าให้กับ input hidden
                        document.getElementById("startDateInput").value = startDate;
                        document.getElementById("endDateInput").value = endDate;

                        // submit ฟอร์ม
                        document.getElementById("reportForm").submit();
                    }

                    flatpickr("#datepicker1", {
                        dateFormat: "Y-m-d",
                        onChange: function(selectedDates, dateStr, instance) {
                            document.getElementById("selectedDate1").textContent = "จากวันที่ : " + dateStr;
                        },
                    });

                    flatpickr("#datepicker2", {
                        dateFormat: "Y-m-d",
                        onChange: function(selectedDates, dateStr, instance) {
                            document.getElementById("selectedDate2").textContent = "ถึง : " + dateStr;
                        },
                    });
                </script>
            </div>

            <div class='container_in'>
            <table>
                <tr>
                    <!-- <th>
                    <div id="checkAll">
                        <input type="checkbox" id="selectAll" onchange="toggleCheckboxes()">
                        <label for="selectAll">Select All</label>
                    </div>
                    </th> -->
                    <th>PurchaseNo</th>
                    <th>CustomerId</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Purchase</th>
                    <th>Invoice</th>
                    <!-- <th>Update</th> -->
                    <!-- <th>Delete</th> -->
                </tr>
                <?php
                    if(mysqli_num_rows($result) <= 0) {
                        echo 'ไม่มีรายการที่คุณตามหา';
                    }
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['PurchaseNo']}</td>";
                            echo "<td>{$row['CustNo']}</td>";
                            echo "<td>{$row['Date']}</td>";
                            echo "<td>{$row['Total']}</td>";
                            echo "<td>{$row['Status']}</td>";
                            $purchaseNo = $row['PurchaseNo'];
                            echo "<td>
                                    <a href='print_purchase_L.php?purchaseNo=$purchaseNo&custno={$row['CustNo']}'>
                                        <img class='img_con2' src='img/print.jpg'>  
                                    </a>
                                </td>";
                            echo"<td>
                                    <a href='print_invoice_L.php?purchaseNo=$purchaseNo&custno={$row['CustNo']}'>
                                        <img class='img_con2'  src='img/print.jpg'>  
                                    </a>
                                </td>";
                            // echo "<td><a href='order/update.php?data=$purchaseNo'><img class='img_con2' src='img/edit.png'></a></td>";
                            // echo "<td><a href='order/delete.php?data=$id'><img src='img/delete.png'></a></td>";
                            echo "</tr>";
                        }
                        //$lastOrderNo = $purchaseNo;

                ?>
            </table>
            </div>
            <?php
                mysqli_close($cx);
            ?>
        </div>
    </div>
</div>
</body>


</style>        
</html>