<div class="sidebar">
  <div class="menu-title">HỌC SINH</div>
  <a href="index.php?controller=student&action=examRoom" class="menu-item active">📝 Xem lịch thi</a>
  <a href="index.php?controller=notification&action=index" class="menu-item <?= ($current_page == 'notification') ? 'active' : '' ?>">📢 Xem thông báo</a>
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
