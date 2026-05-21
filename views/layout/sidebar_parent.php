<div class="sidebar">
    <div class="menu-title" style="padding: 10px 20px; font-weight: bold; border-bottom: 1px solid #4a6278; margin-bottom: 10px;">PHỤ HUYNH</div>
    <a href="index.php?controller=parent&action=dashboard" class="menu-item">🏠 Trang chủ</a>
    <a href="index.php?controller=scheduleView&action=parent" class="menu-item">📅 Thời khóa biểu</a>
    <a href="index.php?controller=leaveApplication&action=index" 
        class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'leaveApplication') ? 'active' : '' ?>">
            📝 Đơn xin nghỉ học
        </a>
    <a href="index.php?controller=admission&action=register" class="menu-item">📝 Đăng ký tuyển sinh</a>
    <a href="index.php?controller=notificationParent&action=index" class="menu-item">📢 Xem thông báo</a>
    <a href="index.php?controller=scoreView&action=index" class="menu-item ">📊 Xem điểm</a>
    
</div>

<style>
.sidebar {
    width: 250px;
    background: #34495e;
    color: #ecf0f1;
    padding: 20px 0;
}
.menu-title {
    padding: 10px 20px;
    font-weight: bold;
    border-bottom: 1px solid #4a6278;
    margin-bottom: 10px;
}
.menu-item {
    padding: 12px 20px;
    display: block;
    color: #ecf0f1;
    text-decoration: none;
    transition: background 0.3s;
}
.menu-item:hover {
    background: #3c5a78;
}

</style>