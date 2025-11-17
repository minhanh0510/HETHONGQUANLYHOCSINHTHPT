<style>
   /* Tổng thể */
.main-content {
    padding: 20px 30px;
    background-color: #f9f9f9;
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center; /* Căn giữa tiêu đề + mô tả */
}

/* Tiêu đề */
.main-content h2 {
    margin-bottom: 10px;
    color: #333;
    font-size: 26px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

/* Mô tả */
.main-content p {
    color: #555;
    margin-bottom: 25px;
    font-size: 15px;
}

/* FORM CONTAINER – khung chứa form */
.form-container {
    max-width: 650px; /* Tăng rộng form */
    margin: 0 auto;
    background: #fff;
    padding: 30px 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: left; /* Form chữ căn trái cho chuyên nghiệp */
}

/* Form */
.form-container form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}

.form-container form input,
.form-container form select {
    width: 100%;
    padding: 11px 13px;
    margin-bottom: 18px;
    border: 1px solid #d0d0d0;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.2s;
}

.form-container form input:focus,
.form-container form select:focus {
    border-color: #006aff;
    box-shadow: 0 0 0 2px rgba(0,106,255,0.2);
    outline: none;
}

/* Button */
.form-container .btn-success {
    width: 100%;
    padding: 12px 0;
    font-size: 16px;
    font-weight: 600;
    border-radius: 6px;
    background-color: #28a745;
    transition: 0.25s;
}

.form-container .btn-success:hover {
    background-color: #218838;
}

/* Alert */
.alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: solid #f5c6cb;
}

/* Toast */
#toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    min-width: 250px;
    margin-bottom: 10px;
    padding: 15px 20px;
    border-radius: 8px;
    color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(100%);
    transition: .5s;
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast.success { background-color: #4CAF50; }
.toast.error { background-color: #f44336; }

</style>
<?php
include "views/layout/header.php";
include "views/layout/sidebar_admin.php";
?>

<div class="main-content">
    <h2>🛠️ Cấp tài khoản và phân quyền</h2>
    <p>Nhập thông tin để tạo tài khoản mới và phân quyền cho người dùng.</p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="form-container">
    <form method="POST" action="index.php?controller=account&action=save">

        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required>

        <label for="hoVaTen">Họ và tên:</label>
        <input type="text" id="hoVaTen" name="hoVaTen" required>

        <label for="gioiTinh">Giới tính:</label>
        <select id="gioiTinh" name="gioiTinh" required>
            <option value="Nam">Nam</option>
            <option value="Nu">Nữ</option>
        </select>

        <!-- NGÀY SINH MỚI THÊM -->
        <label for="ngaySinh">Ngày sinh:</label>
        <input type="date" id="ngaySinh" name="ngaySinh" required>

        <label for="soDienThoai">Số điện thoại:</label>
        <input type="text" id="soDienThoai" name="soDienThoai" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="diaChi">Địa chỉ:</label>
        <input type="text" id="diaChi" name="diaChi" required>

        <label for="role">Phân quyền:</label>
        <select id="role" name="role" required>
            <option value="admin">Quản trị</option>
            <option value="department">Sở giáo dục</option>
            <option value="teacher">Giáo viên</option>
            <option value="student">Học sinh</option>
            <option value="parent">Phụ huynh</option>
            <option value="management">Ban Giám Hiệu</option>
        </select>
   

        <button type="submit" class="btn btn-success">💾 Lưu</button>
    </form>
</div>



<?php include "views/layout/footer.php"; ?>