
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <?php include 'navbar.html'; ?>
        <div class="body_container">
            <div class="top_container">
                <h1>สรุปข้อมูลของกิจการในวันนี้</h1>
                <h5>One Tambon One Product </h5>
            </div>
            <div class="chart_container">
                    <div class="box_container">
                        <form method="post" action="manage_order.php">
                            <input type="hidden" name="search" value="Waiting for packing">
                            <button type="submit"><h3>รายการใบสั่งซื้อที่ต้องจัดส่ง</h3></button>
                        </form>
                    </div>
                    <div class="box_container">
                        <form method="post" action="manage_order.php">
                            <input type="hidden" name="search" value="Pending">
                            <button type="submit"><h3>รายการใบสั่งซื้อที่ยังไม่ชำระเงิน</h3></button>
                        </form>
                    </div>
            </div>
            <div class="chart_container">
                <div class="chart">
                    <h2>Sales Dashboard</h2>
                    <canvas id="salesChart" ></canvas>
                </div>
                <div class="chart">
                    <h2>Product Sales</h2>
                    <canvas id="salesProduct" ></canvas>
                </div>
                
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
    </div>
</body>

<script>
    fetch('sales_data.php')
        .then(response => response.json())
        .then(data => {
            const dates = data.map(entry => entry.SaleDate);
            const sales = data.map(entry => entry.TotalSales);

            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Total Sales',
                        data: sales,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
</script>
<script>
    fetch('sales_product.php')
        .then(response => response.json())
        .then(data => {
            const productName = data.map(entry => entry.ProductName);
            const sumQty = data.map(entry => entry.SumProductQty);

            const ctx = document.getElementById('salesProduct').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productName,
                    datasets: [{
                        label: 'Total Sales',
                        data: sumQty,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
</script>
</html>