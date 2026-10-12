<?php
session_start();
// Agar user pehle se login hai to seedha dashboard bhej dein
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6e6e 0%, #0a9396 100%);
        }
        .hero-card {
            background: #fff;
            border-radius: 16px;
            padding: 50px 40px;
            max-width: 520px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .hero-icon {
            font-size: 60px;
        }
        .feature-list {
            text-align: left;
            margin: 25px 0;
            padding-left: 0;
            list-style: none;
        }
        .feature-list li {
            padding: 6px 0;
            color: #333;
        }
        .feature-list li::before {
            content: "✔️ ";
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-card">
            <div class="hero-icon">💊</div>
            <h1 class="mt-2" style="color:#0d6e6e;">Pharmacy Management System</h1>
            <p class="text-muted">A complete Sale &amp; Purchase management solution for your pharmacy — manage stock, sales, and purchases all in one place.</p>

            <ul class="feature-list">
                <li>Medicines &amp; Categories Management</li>
                <li>Supplier &amp; Customer Records</li>
                <li>Purchase aur Sale Tracking</li>
                <li>Automatic Stock Update</li>
            </ul>

            <a href="login.php" class="btn btn-success btn-lg w-100 mt-2">Get Started &rarr; Login</a>
        </div>
    </div>
</body>
</html>
