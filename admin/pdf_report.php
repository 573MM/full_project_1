<?php
// Include TCPDF library
require_once('D:/years3/term2/xamp/htdocs/Fullstack/application/app/customer/TCPDF/tcpdf.php');

// ตัวอย่างการเชื่อมต่อ MySQL
$cx = mysqli_connect("localhost", "root", "", "app");

if(isset($_GET["startDate"]) && isset($_GET["endDate"])) {
    $startDate = mysqli_real_escape_string($cx, $_GET["startDate"]);
    $endDate = mysqli_real_escape_string($cx, $_GET["endDate"]);

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
    
    // สร้าง header ของตาราง
    $header_purchase = array('PurchaseNo', 'Status', 'Date','Total', 'CustName');
    // ตรวจสอบว่ามีข้อมูลลูกค้าและรายการสินค้าหรือไม่
    if(mysqli_num_rows($result_purchase) > 0) {
        // Create a new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Report');
        $pdf->SetSubject('Report PDF');

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set font
        $fontname = TCPDF_FONTS::addTTFfont("D:/years3/term2/xamp/htdocs/Fullstack/application/app/customer/TCPDF/fonts/TH Sarabun PSK V-1/THSarabun.ttf", 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14);

        // Add a page
        $pdf->AddPage();

       


        // HTML content for the PDF
        $htmlContent = '<style>
                    h1, h3 {
                        text-align: center;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        //border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    p {
                        margin-top: 10px;
                    }
                </style>';
        $htmlContent .= '<h1 style="text-align: center;">รายงานยอดขาย</h1>';
        $htmlContent .= '<h3 style="text-align: center;">ประจำวันที่ ' . $startDate . ' ถึง ' . $endDate . '</h3>';

        date_default_timezone_set('Asia/Bangkok');
        $htmlContent .= '<table border="0">';
        $htmlContent .= '<tr><td>บริษัท โอทอปออนไลน์ จำกัด</td></tr>';
        $htmlContent .= '<tr><td>พิมพ์เมื่อวันที่ ' . date("d/m/Y") . '</td></tr>';

        // Add order details table
        $htmlContent .= '<tr><td>รายละเอียด</td></tr>';
        $htmlContent .= '</table>';


        $htmlContent .= '<table border="1">
                            <tr style="background-color:rgb(192, 192, 246)">
                                <th>ลำดับที่</th>
                                <th>เลขที่ใบสั่งซื้อ</th>
                                <th>ชื่อลูกค้า</th>
                                <th>ราคารวม</th>
                                <th>กำไร</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะใบสั่งซื้อ</th>
                            </tr>';

        // Fetch and display order details
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
            $htmlContent .= '<tr>
                                <td>'.$i.'</td>
                                <td>' .$row['PurchaseNo'] .'</td>
                                <td>'.$row['CustName'].'</td>
                                <td>'.$row['Total'].'</td>
                                <td>'.$totalProfit.'</td>
                                <td>'.$row['Date'].'</td>
                                <td>'.$row['Status'].'</td>
                            </tr>';
        }
        $htmlContent .= '<tr>
        <td></td>
        <td></td>
        <td>รวม</td>
        <td>'.$sumTotal.'</td>
        <td>'.$sumTotalCost.'</td>
        <td>'.$sumTotalProfit.'</td>
        <td></td>
        <td></td>
    </tr>';
        $htmlContent .= '</table>';
        mysqli_close($cx);
        // Display total
        $htmlContent .= '<p>ยอดขายทั้งหมด <?php echo $i; ?> รายการ</p>            
                        <p>ยอดขายรวม '.$sumTotal.' บาท</p>
                        <p>ต้นทุนรวม '.$sumTotalCost.' บาท</p>
                        <p>กำไรรวม '.$sumTotalProfit.' บาท</p>';


        // $htmlContent .= '<p><b>สรุปรายการขาย:</b></p>';
        // $sql_product = "SELECT p.`ProductCode`, p.`ProductName`, SUM(o.`ProductQty`) AS `TotalProductQty`
        // FROM `product` AS p INNER JOIN `order` AS o ON p.`ProductCode` = o.`ProductCode`
        // GROUP BY p.`ProductCode`;";


        // $htmlContent .= '<table border="1">
        // <tr style="background-color:rgb(192, 192, 246)">
        //     <th style="text-align: center;">รหัสสินค้า</th>
        //     <th style="text-align: center;">ชื่อสินค้า</th>
        //     <th style="text-align: center;">จำนวนที่ขายได้</th>
        // </tr>';
        // $result_product = mysqli_query($cx, $sql_product);
        // while ($row = mysqli_fetch_assoc($result_product)){
        // $htmlContent .= '<tr>
        //     <td>' . $row['ProductCode']. '</td>
        //     <td>' . $row['ProductName'] . '</td>
        //     <td>' . $row['TotalProductQty'] . '</td>
        // </tr>';
        // }
        // $htmlContent .= '</table>';

        // Write HTML content to PDF
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('report'.$startDate.'_'.$endDate.'.pdf', 'I');

        // Close MySQL connection
        mysqli_close($cx);
    } else {
        echo "ไม่พบข้อมูลรายการสินค้าในช่วงเวลาดังที่เลือก";
    }
} else {
    echo "คุณยังไม่ได้เลือกวันที่";
}
?>
