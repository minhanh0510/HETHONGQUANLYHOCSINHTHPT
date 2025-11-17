<?php
// views/layout/sidebar_teacher.php
?>
<aside class="main-sidebar">
    <!-- User Info -->
    <div class="user-panel">
        <div class="user-avatar">
            <?php 
            $name = isset($user['hoVaTen']) ? $user['hoVaTen'] : 'GV';
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials .= mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8');
                }
            }
            echo mb_substr($initials, 0, 2, 'UTF-8');
            ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= isset($user['hoVaTen']) ? htmlspecialchars($user['hoVaTen']) : 'Giáo viên' ?></div>
        </div>
        <div class="user-role">Giáo viên</div>
    </div>

    <!-- Menu Title -->
    <div class="menu-title">MENU CHÍNH</div>

    <!-- Navigation Menu -->
    <nav class="sidebar-menu">
        <a href="index.php?controller=classroom&action=index" 
           class="menu-item <?= (!isset($_GET['action']) || $_GET['action'] === 'index') ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Trang chủ</span>
        </a>

        <a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?? 'L10A1' ?>" 
           class="menu-item <?= (isset($_GET['action']) && $_GET['action'] === 'manage') ? 'active' : '' ?>">
            <i class="fas fa-chalkboard"></i>
            <span>Quản lý lớp học</span>
        </a>

        <a href="index.php?controller=classroom&action=studentList" 
           class="menu-item <?= (isset($_GET['action']) && $_GET['action'] === 'studentList') ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Danh sách lớp</span>
        </a>

        <!-- ✅ SỬA: Đổi action từ 'index' thành 'teacherIndex' -->
        <a href="index.php?controller=notification&action=teacherIndex" 
           class="menu-item <?= (isset($_GET['controller']) && $_GET['controller'] === 'notification') ? 'active' : '' ?>">
            <i class="fas fa-bell"></i>
            <span>Thông báo</span>
            <span class="badge-new">Mới</span>
        </a>
        <a href="index.php?controller=feedbackEvaluation&action=showClasses" 
           class="menu-item <?= (isset($_GET['action']) && $_GET['action'] === 'showClasses') ? 'active' : '' ?>">
            <i class="fas fa-edit"></i>
            <span>Nhận xét và đánh giá</span>
        </a>
    </nav>
</aside>

<style>
/* Sidebar - FIX: Để sidebar bắt đầu DƯỚI header */
.main-sidebar {
    position: fixed;
    left: 0;
    top: 60px; /* Thêm khoảng cách cho header */
    width: 250px;
    height: calc(100vh - 60px); /* Trừ đi chiều cao header */
    background: #2c3e50;
    color: #ecf0f1;
    overflow-y: auto;
    z-index: 999; /* Giảm z-index xuống thấp hơn header */
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* User Panel */
.user-panel {
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.user-avatar {
    width: 70px;
    height: 70px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #2ecc71);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.user-info {
    margin-top: 8px;
    text-align: center;
}

.user-name {
    font-size: 15px;
    font-weight: 600;
    color: #ecf0f1;
    margin-bottom: 0;
    text-align: center;
    width: 100%;
    display: block;
}

.user-role {
    font-size: 12px;
    color: #95a5a6;
    text-align: center;
    display: block;
    margin-top: 5px;
}

/* Menu Title */
.menu-title {
    padding: 18px 20px 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #7f8c8d;
    text-transform: uppercase;
}

/* Menu Items */
.sidebar-menu {
    padding: 8px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 13px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.menu-item i {
    width: 24px;
    font-size: 16px;
    margin-right: 12px;
}

.menu-item span {
    font-size: 14px;
}

.menu-item:hover {
    background: rgba(52, 152, 219, 0.1);
    border-left-color: #3498db;
    color: #fff;
    text-decoration: none;
}

.menu-item.active {
    background: #3498db;
    border-left-color: #2ecc71;
    color: #fff;
    font-weight: 600;
}

.menu-item.logout {
    color: #e74c3c;
    margin-top: 20px;
}

.menu-item.logout:hover {
    background: rgba(231, 76, 60, 0.1);
    border-left-color: #e74c3c;
}

/* Badge */
.badge-new {
    margin-left: auto;
    background: #f39c12;
    color: #fff;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
}

/* Scrollbar */
.main-sidebar::-webkit-scrollbar {
    width: 6px;
}

.main-sidebar::-webkit-scrollbar-track {
    background: #2c3e50;
}

.main-sidebar::-webkit-scrollbar-thumb {
    background: #34495e;
    border-radius: 3px;
}

.main-sidebar::-webkit-scrollbar-thumb:hover {
    background: #4a6278;
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