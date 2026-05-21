<div class="sidebar">
  <div class="menu-title">HỌC SINH</div>
    <a href="index.php?controller=student&action=dashboard" class="menu-item">🏠 Trang chủ</a>
    <a href="index.php?controller=scheduleView&action=student" class="menu-item">📅 Thời khóa biểu</a>
    <a href="index.php?controller=student&action=examRoom" class="menu-item">📝 Xem lịch thi</a>
        <a href="index.php?controller=assignmentStudent&action=index" class="menu-item">📚 Xem bài tập</a>
    <a href="index.php?controller=scoreView&action=index" class="menu-item ">📊 Xem điểm</a>
    <a href="index.php?controller=notification&action=index" class="menu-item">📢 Xem thông báo</a>
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