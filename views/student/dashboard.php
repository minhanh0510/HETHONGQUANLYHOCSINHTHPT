<?php
// Kiểm tra session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=auth&action=login");
    exit;
}

$user = $_SESSION['user'];
if ($user['role'] !== 'student') {
    die("Bạn không có quyền truy cập trang này");
}
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_student.php"; ?>

<div class="main-content">
    <!-- Page Header - Sửa CSS để nằm ngang -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef;">
        <h1 style="font-size: 1.8rem; margin: 0; color: #2c3e50;">🎓 Dashboard - Học sinh</h1>
        <div style="color: #6c757d; font-size: 1rem;">
            <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y') ?>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="welcome-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h4 class="mb-1">Chào mừng, <?= htmlspecialchars($user['hoVaTen'] ?? $user['username'] ?? 'Học sinh') ?>! 👋</h4>
                <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">
                    Chúc bạn một ngày học tập hiệu quả và đầy hứng khởi!
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <div class="stat-icon" style="font-size: 2rem; margin-bottom: 15px; color: #3498db;">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-number" style="font-size: 2rem; font-weight: bold; color: #2c3e50;">12</div>
            <div class="stat-label" style="color: #7f8c8d; font-size: 1rem;">Môn học</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <div class="stat-icon" style="font-size: 2rem; margin-bottom: 15px; color: #3498db;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number" style="font-size: 2rem; font-weight: bold; color: #2c3e50;">8.5</div>
            <div class="stat-label" style="color: #7f8c8d; font-size: 1rem;">Điểm TB HK1</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <div class="stat-icon" style="font-size: 2rem; margin-bottom: 15px; color: #3498db;">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-number" style="font-size: 2rem; font-weight: bold; color: #2c3e50;">5</div>
            <div class="stat-label" style="color: #7f8c8d; font-size: 1rem;">Thông báo mới</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <div class="stat-icon" style="font-size: 2rem; margin-bottom: 15px; color: #3498db;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number" style="font-size: 2rem; font-weight: bold; color: #2c3e50;">3</div>
            <div class="stat-label" style="color: #7f8c8d; font-size: 1rem;">Bài tập chờ</div>
        </div>
    </div>

    <!-- Dashboard Cards - Sửa CSS để nằm ngang -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
        <div class="dashboard-card" style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid #3498db; height: 100%;">
            <h4 style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="fas fa-clock text-warning" style="margin-right: 8px;"></i>Lịch học hôm nay
            </h4>
            <div class="placeholder-content" style="text-align: center; padding: 30px 20px; color: #6c757d;">
                <div class="placeholder-icon" style="font-size: 2rem; margin-bottom: 15px; opacity: 0.5;">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h5 style="margin-bottom: 10px;">Chức năng đang phát triển</h5>
                <p style="margin: 0;">Dữ liệu lịch học sẽ được tích hợp từ module Thời khóa biểu</p>
            </div>
        </div>
        
        <div class="dashboard-card" style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid #3498db; height: 100%;">
            <h4 style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="fas fa-tasks text-success" style="margin-right: 8px;"></i>Bài tập gần đây
            </h4>
            <div class="placeholder-content" style="text-align: center; padding: 30px 20px; color: #6c757d;">
                <div class="placeholder-icon" style="font-size: 2rem; margin-bottom: 15px; opacity: 0.5;">
                    <i class="fas fa-tasks"></i>
                </div>
                <h5 style="margin-bottom: 10px;">Chức năng đang phát triển</h5>
                <p style="margin: 0;">Dữ liệu bài tập sẽ được tích hợp từ module Bài tập</p>
            </div>
        </div>
    </div>

    <!-- Image Section -->
    <div class="dashboard-card mt-4" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; margin-top: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid #3498db;">
        <h4 style="display: flex; align-items: center;">
            <i class="fas fa-image text-info" style="margin-right: 8px;"></i>Hoạt động học tập
        </h4>
        <div class="text-center mt-3">
            <img src="/PTUD_HETHONGQUANLYHOCSINHC3/assets/img/student-dashboard.jpg" 
                 alt="Hoạt động học tập" 
                 style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);"
                 onerror="this.style.display='none'">
            <div class="mt-2 text-muted">
                <small>Hình ảnh minh họa hoạt động học tập</small>
            </div>
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>