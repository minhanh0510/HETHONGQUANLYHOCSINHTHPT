<?php
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$error = $error ?? $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống QLGD</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="login-container">
        <div class="login-background">
            <div class="login-card">
                <div class="login-image">
                    <img src="assets/img/team.jpg" alt="Hệ thống QLGD">
                    
                </div>
                
                <form class="login-form" method="POST" action="index.php?controller=auth&action=login">
                    <div class="form-header">
                        <h1>ĐĂNG NHẬP</h1>
                        <p>Vui lòng nhập thông tin đăng nhập</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">
                            <i class='bx bx-error-circle'></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <div class="input-container">
                            <i class='bx bxs-user'></i>
                            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required 
                                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-container">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            <span class="toggle-password">
                                <i class='bx bx-hide'></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn">
                        <span>Đăng nhập</span>
                        <i class='bx bx-log-in'></i>
                    </button>

                    <div class="form-footer">
                        <p>Liên hệ quản trị viên nếu quên mật khẩu</p>
                        <div class="support-info">
                            <i class='bx bx-phone'></i>
                            <span>Hotline: 0392.594.323</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>