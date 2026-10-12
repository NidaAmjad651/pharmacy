<?php
require "config.php";
require_login();

$page_title = "Dashboard";

$total_medicines = $conn->query("SELECT COUNT(*) as c FROM medicines")->fetch_assoc()['c'];
$low_stock       = $conn->query("SELECT COUNT(*) as c FROM medicines WHERE quantity <= 10")->fetch_assoc()['c'];
$today_sale      = $conn->query("SELECT IFNULL(SUM(total_amount),0) as t FROM sales WHERE sale_date = CURDATE()")->fetch_assoc()['t'];
$today_purchase  = $conn->query("SELECT IFNULL(SUM(total_amount),0) as t FROM purchases WHERE purchase_date = CURDATE()")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pharmacy System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="row g-0">
    <?php include "includes/sidebar.php"; ?>
    <div class="col-md-10">
        <?php include "includes/topbar.php"; ?>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card card-stat p-3 text-center">
                        <h6 class="text-muted">Total Medicines</h6>
                        <h3 class="text-success"><?php echo $total_medicines; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat p-3 text-center">
                        <h6 class="text-muted">Low Stock (&le;10)</h6>
                        <h3 class="text-danger"><?php echo $low_stock; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat p-3 text-center">
                        <h6 class="text-muted">Today's Sale</h6>
                        <h3 class="text-primary">Rs. <?php echo number_format($today_sale,2); ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat p-3 text-center">
                        <h6 class="text-muted">Today's Purchase</h6>
                        <h3 class="text-warning">Rs. <?php echo number_format($today_purchase,2); ?></h3>
                    </div>
                </div>
            </div>

            <div class="card card-stat mt-4 p-3">
                <h6>Low Stock Medicines</h6>
                <table class="table table-sm table-bordered mt-2">
                    <tr><th>Medicine</th><th>Quantity</th><th>Sale Price</th></tr>
                    <?php
                    $res = $conn->query("SELECT medicine_name, quantity, sale_price FROM medicines WHERE quantity <= 10");
                    while ($row = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>Rs. <?php echo number_format($row['sale_price'],2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
