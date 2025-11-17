<div class="sidebar">
    <div class="menu-title" style="padding: 10px 20px; font-weight: bold; border-bottom: 1px solid #4a6278; margin-bottom: 10px;">PHỤ HUYNH</div>
    <a href="/PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=parent&action=dashboard" class="menu-item <?= ($current_page == 'dashboard') ? 'active' : '' ?>">🏠 Trang chủ</a>
    <a href="/PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=admission&action=register" class="menu-item <?= ($current_page == 'register') ? 'active' : '' ?>">📝 Đăng ký tuyển sinh</a>
<a href="index.php?controller=notificationParent&action=index" class="menu-item <?= ($current_page == 'notification') ? 'active' : '' ?>">📢 Xem thông báo</a>    
<a href="/PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=auth&action=logout" class="menu-item">🚪 Đăng xuất</a>
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
.menu-item.active {
    background: #3498db;
    border-left: 4px solid #2980b9;
}
</style>