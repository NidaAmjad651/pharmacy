<?php
require "config.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pharmacy Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>
    <div class="split-wrap">
        <!-- LEFT: Branding panel -->
        <div class="split-left">
            <div class="brand-logo"><i class="ti ti-first-aid-kit" style="color:#7fe7d4; margin-right:6px;"></i>Pharmacy<span>Portal</span></div>

            <div>
                <h1>Manage smarter.<br>Sell faster.</h1>
                <p>One platform to handle medicines, suppliers, purchases and sales — with stock updated automatically the moment a transaction happens.</p>
            </div>

            <div class="feature-row">
                <div>
                    <i class="ti ti-package" style="font-size:22px; color:#7fe7d4;"></i>
                    <div class="num mt-1">Stock</div>
                    <div class="label">Live inventory</div>
                </div>
                <div>
                    <i class="ti ti-shopping-cart" style="font-size:22px; color:#7fe7d4;"></i>
                    <div class="num mt-1">Sale</div>
                    <div class="label">Quick billing</div>
                </div>
                <div>
                    <i class="ti ti-refresh" style="font-size:22px; color:#7fe7d4;"></i>
                    <div class="num mt-1">Auto</div>
                    <div class="label">Instant updates</div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Login form -->
        <div class="split-right">
            <div class="split-form">
                <h2 class="fw-bold" style="color:#0f1b2d;">Welcome back</h2>
                <p class="text-muted">Log in to continue to your dashboard.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">Log In</button>
                </form>

                <p class="text-center text-muted mt-4" style="font-size:14px;">
                    <a href="index.php" style="color:#0d6e6e; text-decoration:none;">&larr; Back to Home</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
