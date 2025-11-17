<div class="sidebar">
  <h3>📋 Menu Admin</h3>

  <a href="index.php?controller=studentAdmin&action=index" class="nav-link">👩‍🎓 Quản lý hồ sơ học sinh</a>
  <a href="index.php?controller=scheduleAdmin&action=index" class="nav-link">📚 Quản lý lịch học</a>
  <a href="index.php?controller=assignment&action=index" class="nav-link">🏫 Phân công khối lớp</a>
  <a href="index.php?controller=roomAssignment&action=index" class="nav-link">🏠 Phân công phòng thi</a>
  <a href="index.php?controller=account&action=index" class="nav-link">🛠️ Cấp tài khoản và phân quyền</a>

  
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