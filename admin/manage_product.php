<?php
    session_start();
    $adminRole = $_SESSION['adminRole'];
    $cx = mysqli_connect("localhost","root","","app");


    if(isset($_POST['search'])){
        $search_input = $_POST['search'];
        $query = "SELECT * FROM product WHERE LOWER(ProductNo) LIKE LOWER('%$search_input%') OR LOWER(ProductName) LIKE LOWER('%$search_input%') OR LOWER(Category) LIKE LOWER('%$search_input%') ORDER BY ProductNo";
    }else{
        $query = "SELECT * FROM product  ORDER BY ProductNo";
    }
    
    $result = mysqli_query($cx, $query);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Product Data</title>
    <style>
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
        .img_con2{
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
    <?php 
        if($adminRole == 'H'){
            include 'navbar.html';
        }else if($adminRole =='L'){
            include 'navbarL.html';
        }else{
            echo 'การเข้าถึงไม่ถูกต้อง';
            session_destroy();
        }
         ?>
    <div class='body_container'>
        
        <h2>Product Data</h2>
        <div class='content'>
        <div class="top">
            <div class="date">
                <?php
                    date_default_timezone_set("Asia/Bangkok");
                ?>
                <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
            </div>
            <div class="search">
                <p>ค้นหา </p>
                <form method="post" action="manage_product.php">
                    <input type="text" name="search">
                    <button type="submit">
                        <img class='img_con2' src="img/search.png" alt="ค้นหา">
                    </button>
                </form>
            </div>
            
        </div>

        <table>
            <tr>
                <!-- <th>
                <div id="checkAll">
                    <input type="checkbox" id="selectAll" onchange="toggleCheckboxes()">
                    <label for="selectAll">Select All</label>
                </div>
                </th> -->
                <th>ProductNo</th>
                <th>ProductName</th>
                <th>Category</th>
                <th>PricePerUnit</th>
                <th>CostPrice</th>
                <th>StockQty</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['ProductNo']}</td>";
                        echo "<td>{$row['ProductName']}</td>";
                        echo "<td>{$row['Category']}</td>";
                        echo "<td>{$row['PricePerUnit']}</td>";
                        echo "<td>{$row['Cost']}</td>";
                        echo "<td>{$row['Qty']}</td>";
                        $id = $row['ProductNo'];
                        echo "<td><a href='product/update.php?data=$id'><img class='img_con' src='img/edit.png'></a></td>";
                        echo "<td><a href='product/delete.php?data=$id'><img class='img_con2' src='img/delete.png'></a></td>";
                        echo "</tr>";
                    }
                    //$lastProductCode = $id;

            ?>
        </table>
        <center><!-- <a href="product/insert_form.html"> -->
            
        <a href="product/insert_form.php">
                <div class="insert_button">
                    <img class='img_con2' src="img/insert.jpg">
                    <p>Insert Product</p>
                </div>
            </a></center>
        <?php
            mysqli_close($cx);
        ?>
        </div>
    </div>
</div>
</body>


</style>        
</html>