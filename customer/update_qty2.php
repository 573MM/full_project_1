<?php
session_start();
$cx = mysqli_connect("localhost", "root", "", "app");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productNo']) && isset($_POST['qty'])) {
    
    $custId = $_SESSION['custId'];
    $productNo = $_POST['productNo'];
    $quantity = $_POST['qty'];

    // Update the product quantity in the database
    for ($i = 0; $i < count($productNo); $i++) {
        $updateQuery = "UPDATE shoppingcart SET ProductQty = '{$quantity[$i]}' WHERE ProductNo = '{$productNo[$i]}'";
        $updateResult = mysqli_query($cx, $updateQuery);
    }

    if ($updateResult) {
        // Redirect to test_make.php with updated product quantity
        $productNoString = implode("&productNo[]=", $productNo);
        $qtyString = implode("&qty[]=", $quantity);
        $urlParameters = "&productNo[]=$productNoString&qty[]=$qtyString";
        header("Location: test_makeb.php?custno=$custId$urlParameters");
        exit;
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}

mysqli_close($cx);
?>
