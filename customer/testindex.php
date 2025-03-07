<?php
    session_start();
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_POST['search'])){
        $search_input = $_POST['search'];
        $query = "SELECT * FROM product WHERE LOWER(ProductNo) LIKE LOWER('%$search_input%') OR LOWER(ProductName) LIKE LOWER('%$search_input%') OR LOWER(Category) LIKE LOWER('%$search_input%') ORDER BY ProductNo";
    }else{
        $query = "SELECT * FROM product ORDER BY ProductNo";
    }
    

    
    $result = mysqli_query($cx, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOPPING</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        body{
            margin: 0rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #e3e3e3;
        }
        a{
            text-decoration: none;
            color: black;
        }
        .container_top{
            width: 100%;
            height: 10rem;
            background: #ff8ad4;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .ul{
            margin: 0px auto;
        }
        .item{
            max-width: 1040px;
            display:grid;
            grid-template-columns: repeat(5,1fr);
        }
        .item_box{
            margin: 0.25rem;
            max-width: 220px;
            height: 280px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            border: 2px solid transparent;
        }
        .item_box img{
            width: 100%;
            height: 197px;
            border-radius: 10px 10px 0px 0px;
            object-fit: cover; /* ไม่ต้องบีบรูปภาพแต่จะทำให้เต็ม container */
            object-position: center center;
        }
        .item_box:hover {
            border: 2px solid #ff8ad4; /* เปลี่ยนสีขอบเมื่อ hover */
        }
        .info {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .info p{
            margin: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        /* .item_box img {
            display: block;
            margin: 0 auto; 
            border: 5px ;
        } */
    </style>
    <style>
    .navbar {
            display: flex;
            width: -webkit-fill-available;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            height: 30px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 8px 120px 8px 15px;
            border: 1px solid #ced4da;
            border-radius: 5px 0 0 5px;
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .user-profile p {
            margin: 0 10px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            padding: 8px 0;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
            right: 0; 
            margin-top: 128px; 

        }
        .dropdown-content a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #000;
        }

        .user-profile:hover .dropdown-content {
            display: block;
        }

        .cart img {
            width: 30px;
            height: 30px;
            padding : 0 30px 0 20px
        }
        .element-right {
            display: flex;
            align-items: center; 
        }

        .slider-container {
            position: relative;
            max-width: 100%;
            
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease;
        }

        .slide {
            flex: 0 0 100%;
        }

        .slider img {
            width: 100%;
    
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 20px;
            z-index: 2;
        }

        .prev {
            left: 0;
        }

        .next {
            right: 0;
        }
        .slidebg{
            position: absolute; 
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .slidbg img{
            height: 100%;
        }

    </style>
</head>
<body>

<?php
    
    $custId = isset($_SESSION['custId']) ? $_SESSION['custId'] : "";
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
    
?>
    <?php  include 'navbar.html';?>
    
    

    <div class="slider-container">
        
        <div class="slider">
            <div class="slide">
                <img src="img/imgslide1.png" alt="Image 1">
            </div>
            <div class="slide">
                <img src="img/imgslide2.png" alt="Image 2">
            </div>
            <div class="slide">
                <img src="img/imgslide3.png" alt="Image 3">
            </div>
            <div class="slide">
                <img src="img/imgslide4.png" alt="Image 4">
            </div>
            <div class="slide">
                <img src="img/imgslide5.png" alt="Image 5">
            </div>
            <div class="slide">
                <img src="img/imgslide6.png" alt="Image 6">
            </div>
        </div>
       
    </div>

    
    
    <div class="item">
        <?php?>  
            <?php       
                if ($result->num_rows > 0) {
                    // แสดงข้อมูลที่ดึงมาจากฐานข้อมูล
                    while($row = $result->fetch_assoc()) {
                        echo "<a href='product_item.php?ProductNo={$row['ProductNo']}'>";
                        echo '<div class="item_box">';
                        //echo '<img src="img/' . $row["ProductNo"] . '">';
                        echo '<img src="img/'.$row["ProductNo"].'.jpg">';
                        echo '<div class="info">';
                        echo '<p class="name_product">' . $row["ProductName"] . '</p>';
                        echo  '<div class = "category">';
                        echo  '<p >'.$row["Category"] . '</p> </div>';
                        echo '<div class="price">';
                        echo '<p>' . $row["PricePerUnit"] . ' บาท</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo "</a>";
                    }
                } else {
                    echo "0 ผลลัพธ์";
                }
                $cx->close();
        ?>
            
    </div>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            const slides = document.getElementsByClassName("slide");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}
            slides[slideIndex-1].style.display = "block";
            setTimeout(showSlides, 3000); 
        }
    </script>


   
</body>

</html>