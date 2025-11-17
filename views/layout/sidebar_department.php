<div class="sidebar">
    <a href="index.php?controller=department&action=index">
    <div class="menu-title">SỞ GIÁO DỤC</div>
</a>
    <a href="index.php?controller=quota&action=index" class="menu-item active">📊 Nhập chỉ tiêu</a>
    <a href="index.php?controller=examScore&action=index" class="menu-item active ">🏠 Nhập điểm tuyển sinh</a>
    <a href="index.php?controller=quota&action=index" class="menu-item">🚪 Xếp danh sách thi tuyển sinh</a>
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