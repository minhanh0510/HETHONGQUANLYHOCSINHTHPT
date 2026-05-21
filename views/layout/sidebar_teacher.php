<?php
// views/layout/sidebar_teacher.php - UPDATED
?>
<aside class="main-sidebar">
    <!-- Menu Title -->
    <div class="menu-title">GIÁO VIÊN</div>

    <!-- Navigation Menu -->
    <nav class="sidebar-menu">
        <a href="index.php?controller=teacher&action=dashboard" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'teacher' && (!isset($_GET['action']) || $_GET['action'] === 'dashboard')) ? 'active' : '' ?>">
            🏠 Trang chủ
        </a>

        <a href="index.php?controller=scheduleView&action=teacher&view=week" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'scheduleView' && isset($_GET['action']) && $_GET['action'] === 'teacher') ? 'active' : '' ?>">
            📅 Thời khóa biểu
        </a>
        
        <a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?? 'L10A1' ?>" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'classroom' && isset($_GET['action']) && $_GET['action'] === 'manage') ? 'active' : '' ?>">
            📚 Quản lý lớp học
        </a>
        
        <a href="index.php?controller=classroom&action=studentList" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'classroom' && isset($_GET['action']) && $_GET['action'] === 'studentList') ? 'active' : '' ?>">
            👥 Danh sách lớp
        </a>        
        
        <a href="index.php?controller=hanhKiem&action=index" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'hanhKiem') ? 'active' : '' ?>">
            ✅ Xếp loại hạnh kiểm
        </a>
        
        <a href="index.php?controller=assignmentTeacher&action=index" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'assignmentTeacher') ? 'active' : '' ?>">
            📝 Quản lý bài tập
        </a>
        
        <a href="index.php?controller=notificationTeacher&action=index" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'notificationTeacher') ? 'active' : '' ?>">
            📢 Thông báo
        </a>
        <a href="index.php?controller=feedbackEvaluation&action=showClasses" 
           class="menu-item <?= (isset($_GET['action']) && $_GET['action'] === 'showClasses') ? 'active' : '' ?>">
            <i class="fas fa-edit"></i>
            <span>Nhận xét và đánh giá</span>
        </a>

    </nav>
</aside>

<style>
/* ===== Sidebar ===== */
.main-sidebar {
    position: fixed;
    left: 0;
    top: 60px;
    width: 250px;
    height: calc(100vh - 60px);
    background: #34495e;
    color: #ecf0f1;
    overflow-y: auto;
    z-index: 999;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* Menu Title */
.menu-title {
    padding: 15px 20px 10px;
    font-size: 12px;
    font-weight: bold;
    color: #ecf0f1;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 5px;
}

/* Menu Items */
.sidebar-menu {
    padding: 0;
}

.menu-item {
    display: block;
    padding: 12px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.menu-item:hover {
    background: #3c5a78;
    color: #fff;
    text-decoration: none;
    border-left-color: #3498db;
}

.menu-item.active {
    background: #2c3e50;
    color: #fff;
    font-weight: 600;
    border-left-color: #2ecc71;
}

/* Scrollbar */
.main-sidebar::-webkit-scrollbar {
    width: 6px;
}

.main-sidebar::-webkit-scrollbar-track {
    background: #34495e;
}

.main-sidebar::-webkit-scrollbar-thumb {
    background: #4a6278;
    border-radius: 3px;
}

.main-sidebar::-webkit-scrollbar-thumb:hover {
    background: #5a7288;
}

/* Responsive */
@media (max-width: 768px) {
    .main-sidebar {
        left: -250px;
        transition: left 0.3s ease;
    }
    
    .main-sidebar.show {
        left: 0;
    }
}
</style>