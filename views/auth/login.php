<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống QLGD</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
              <div class="logo">QLGD</div>
              <div class="login-title">Đăng nhập hệ thống</div>
              <div class="login-subtitle">Vui lòng nhập tài khoản để tiếp tục</div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=login">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn">Đăng nhập</button>
                <div class="loading" id="loading"></div>
            </form>
        </div>
    </div>
    
    <div class="login-footer">
      <div>Liên hệ quản trị viên nếu quên mật khẩu.</div>
      <div class="copyright">© <?= date('Y') ?> QLGD - Quản lý giáo dục</div>
    </div>
    
    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
