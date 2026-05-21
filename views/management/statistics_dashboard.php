<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<style>
/* CSS với màu pastel */
.main-content {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 70px);
}

.content-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.5);
}

.page-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.page-title i {
    color: #667eea;
    font-size: 1.5rem;
}

.system-alert {
    background: linear-gradient(135deg, #5695f5ff 0%, #fdfafbff 100%);
    color: #4a5568;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.system-alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.system-alert i {
    font-size: 1.8rem;
    margin-top: 2px;
    color: #667eea;
    background: rgba(255, 255, 255, 0.8);
    padding: 12px;
    border-radius: 50%;
}

.system-alert p {
    margin: 0;
    font-size: 1rem;
    line-height: 1.6;
    flex: 1;
}

/* Container chứa 4 card chức năng */
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

/* Card chức năng */
.stat-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 25px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.5);
    position: relative;
    overflow: hidden;
    min-height: 220px;
    display: flex;
    flex-direction: column;
    backdrop-filter: blur(10px);
}

/* Dải màu pastel trên cùng */
.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--card-color-light), var(--card-color));
    transition: all 0.3s ease;
    border-radius: 20px 20px 0 0;
}

.stat-card:hover::before {
    height: 8px;
    opacity: 0.9;
}

/* Hiệu ứng overlay khi hover */
.stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--card-color-light) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

.stat-card:hover::after {
    opacity: 0.1;
}

/* Màu pastel cho từng card */
.stat-card:nth-child(1) { 
    --card-color: #a8edea; 
    --card-color-light: #c4f2ef;
    --card-text-color: #2d7a77;
}
.stat-card:nth-child(2) { 
    --card-color: #fed6e3; 
    --card-color-light: #ffe4ed;
    --card-text-color: #b0577e;
}
.stat-card:nth-child(3) { 
    --card-color: #d4c1fc; 
    --card-color-light: #e5d8fe;
    --card-text-color: #7a5fb5;
}
.stat-card:nth-child(4) { 
    --card-color: #a0e4f1; 
    --card-color-light: #c2eef7;
    --card-text-color: #3a7d8a;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: var(--card-color);
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 1;
}

.stat-card i {
    font-size: 2.5rem;
    color: var(--card-text-color);
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 15px;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.stat-card:hover i {
    transform: scale(1.1) rotate(5deg);
    background: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 12px;
    line-height: 1.3;
}

.card-text {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 20px;
    flex: 1;
    font-size: 0.92rem;
}

.btn {
    padding: 10px 22px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    width: fit-content;
    margin-top: auto;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-card .btn {
    background: var(--card-color);
    color: var(--card-text-color);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.stat-card .btn:hover {
    background: var(--card-text-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* ================== THỐNG KÊ NHANH - 3 CHỈ SỐ ================== */
.quick-stats-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.quick-stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #0d6efd, #f9ffffff, #efc4d2ff);
    background-size: 200% 100%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.section-header {
    font-size: 1.4rem;
    font-weight: 600;
    color: #f7f7f7ff;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.08);
}

.section-header i {
    color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1.6rem;
}

/* Container cho 3 card thống kê */
.quick-stats-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* CHỈ CÒN 3 CARD */
    gap: 25px;
}

/* THỐNG KÊ CARD VỚI MÀU TƯƠI SÁNG */
.quick-stats-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
    min-height: 160px;
}

/* Màu nền cho 3 card thống kê */
.quick-stats-card:nth-child(1) {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    border-color: #1f67a2ff;
}

.quick-stats-card:nth-child(2) {
    background: linear-gradient(135deg, #F3E5F5 0%, #E1BEE7 100%);
    border-color: #CE93D8;
}

.quick-stats-card:nth-child(3) {
    background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
    border-color: #A5D6A7;
}

/* Hiệu ứng hover cho thống kê card */
.quick-stats-card:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Icon trong thống kê card */
.quick-stats-card i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

/* Màu icon theo từng card */
.quick-stats-card:nth-child(1) i {
    color: #1976D2;
    background: rgba(25, 118, 210, 0.1);
}

.quick-stats-card:nth-child(2) i {
    color: #7B1FA2;
    background: rgba(123, 31, 162, 0.1);
}

.quick-stats-card:nth-child(3) i {
    color: #388E3C;
    background: rgba(56, 142, 60, 0.1);
}

.quick-stats-card:hover i {
    transform: scale(1.15) rotate(10deg);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

/* Số liệu thống kê */
.quick-stats-card h3 {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 10px 0;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #2c3e50, #4a5568);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.05);
}

.quick-stats-card:hover h3 {
    transform: scale(1.1);
    background: linear-gradient(135deg, #1a237e, #311b92);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Text mô tả */
.quick-stats-card p {
    color: #546E7A;
    font-size: 0.95rem;
    margin: 0;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Animation cho số đếm */
.count-animate {
    position: relative;
    animation: gentleBounce 0.8s ease;
}

@keyframes gentleBounce {
    0% { transform: scale(1); }
    30% { transform: scale(1.15); }
    60% { transform: scale(0.95); }
    100% { transform: scale(1); }
}

/* Responsive */
@media (max-width: 1200px) {
    .card-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .quick-stats-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 15px;
    }
    
    .card-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .quick-stats-container {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-card {
        padding: 20px;
        min-height: 200px;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .system-alert {
        padding: 15px;
        flex-direction: column;
        text-align: center;
    }
    
    .system-alert i {
        margin: 0 auto 10px;
    }
    
    .quick-stats-card h3 {
        font-size: 2.2rem;
    }
    
    .quick-stats-card i {
        font-size: 2rem;
        padding: 12px;
    }
}

@media (max-width: 576px) {
    .quick-stats-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .stat-card i {
        font-size: 2rem;
        padding: 12px;
    }
    
    .card-title {
        font-size: 1.2rem;
    }
    
    .quick-stats-card {
        padding: 20px;
        min-height: 140px;
    }
    
    .quick-stats-card h3 {
        font-size: 2rem;
    }
}

/* Thêm hiệu ứng hover nhẹ nhàng */
.stat-card, .quick-stats-card, .system-alert {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-chart-bar"></i> Quản lý Thống kê
        </h1>
    </div>
    
    <!-- Thông báo hệ thống 
    <div class="system-alert">
        <i class="fas fa-info-circle"></i>
        <p>Chọn một chức năng thống kê để xem báo cáo chi tiết. Dữ liệu được cập nhật theo thời gian thực từ hệ thống.</p>
    </div>
    -->
    <!-- 4 CHỨC NĂNG VỚI MÀU PASTEL -->
    <div class="card-container">
        <!-- Danh sách học sinh - Màu xanh mint pastel -->
        <div class="stat-card" onclick="location.href='index.php?controller=statistics&action=studentList'">
            <div class="card-body">
                <i class="fas fa-users"></i>
                <h5 class="card-title">Danh sách học sinh</h5>
                <p class="card-text">Xem danh sách học sinh theo lớp, khối, toàn trường với thông tin chi tiết về trạng thái học tập và thông tin cơ bản.</p>
                <a href="index.php?controller=statistics&action=studentList" class="btn">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
            </div>
        </div>
        
        <!-- Danh sách giáo viên - Màu hồng pastel -->
        <div class="stat-card" onclick="location.href='index.php?controller=statistics&action=teacherList'">
            <div class="card-body">
                <i class="fas fa-chalkboard-teacher"></i>
                <h5 class="card-title">Danh sách giáo viên</h5>
                <p class="card-text">Xem danh sách giáo viên theo tổ chuyên môn với khả năng lọc và xem chi tiết thông tin giảng dạy.</p>
                <a href="index.php?controller=statistics&action=teacherList" class="btn">
                    <i class="fas fa-list"></i> Xem chi tiết
                </a>
            </div>
        </div>
        
        <!-- Danh sách phân công - Màu tím pastel -->
        <div class="stat-card" onclick="location.href='index.php?controller=statistics&action=assignmentList'">
            <div class="card-body">
                <i class="fas fa-tasks"></i>
                <h5 class="card-title">Danh sách phân công</h5>
                <p class="card-text">Xem phân công giáo viên theo bộ môn, lớp với thông tin số lớp phụ trách, số tiết/tuần và khối.</p>
                <a href="index.php?controller=statistics&action=assignmentList" class="btn">
                    <i class="fas fa-clipboard-list"></i> Xem chi tiết
                </a>
            </div>
        </div>
        
        <!-- Cơ cấu học sinh - Màu lam pastel -->
        <div class="stat-card" onclick="location.href='index.php?controller=statistics&action=studentStructure'">
            <div class="card-body">
                <i class="fas fa-chart-pie"></i>
                <h5 class="card-title">Cơ cấu học sinh</h5>
                <p class="card-text">Thống kê tỷ lệ nam/nữ theo lớp, khối, toàn trường hiển thị dưới dạng bảng và biểu đồ trực quan.</p>
                <a href="index.php?controller=statistics&action=studentStructure" class="btn">
                    <i class="fas fa-chart-pie"></i> Xem thống kê
                </a>
            </div>
        </div>
    </div> 
    
    <!-- THỐNG KÊ NHANH - CHỈ CÒN 3 CHỈ SỐ -->
    <div class="quick-stats-section">
        <div class="section-header">
            <i class="fas fa-tachometer-alt"></i> Thống kê nhanh
        </div>
        
        <div class="quick-stats-container">
            <!-- Tổng học sinh - Xanh dương tươi -->
            <div class="quick-stats-card">
                <i class="fas fa-users"></i>
                <h3 id="totalStudents">0</h3>
                <p>Tổng học sinh</p>
            </div>
            
            <!-- Tổng giáo viên - Tím tươi -->
            <div class="quick-stats-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3 id="totalTeachers">0</h3>
                <p>Tổng giáo viên</p>
            </div>
            
            <!-- Tổng lớp học - Xanh lá tươi -->
            <div class="quick-stats-card">
                <i class="fas fa-school"></i>
                <h3 id="totalClasses">0</h3>
                <p>Tổng lớp học</p>
            </div>
            <!-- ĐÃ XÓA ĐIỂM TB TOÀN TRƯỜNG -->
        </div>
    </div>
</div>

<script>
// Lấy thống kê nhanh
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu thống kê thực tế
    fetchQuickStats();
    
    // Cập nhật mỗi 30 giây
    setInterval(fetchQuickStats, 30000);
    
    // Hiệu ứng click cho card chức năng
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                const link = this.querySelector('a.btn').href;
                window.location.href = link;
            }
        });
        
        // Hiệu ứng hover mượt mà
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.06)';
        });
    });
    
    // Hiệu ứng hover cho thống kê nhanh
    document.querySelectorAll('.quick-stats-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.05)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.08)';
        });
    });
});

function fetchQuickStats() {
    fetch('index.php?controller=statistics&action=getQuickStats')
    .then(response => response.json())
    .then(data => {
        // CHỈ CÒN 3 CHỈ SỐ
        updateWithAnimation('totalStudents', data.totalStudents, false);
        updateWithAnimation('totalTeachers', data.totalTeachers, false);
        updateWithAnimation('totalClasses', data.totalClasses, false);
        // ĐÃ XÓA avgScore
    })
    .catch(error => {
        console.error('Error fetching quick stats:', error);
        // Giá trị mặc định
        document.getElementById('totalStudents').textContent = '0';
        document.getElementById('totalTeachers').textContent = '0';
        document.getElementById('totalClasses').textContent = '0';
    });
}

function updateWithAnimation(elementId, targetValue, isDecimal = false) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const currentValue = isDecimal 
        ? parseFloat(element.textContent) || 0 
        : parseInt(element.textContent) || 0;
    
    if (currentValue === targetValue) return;
    
    // Thêm class animation
    element.classList.add('count-animate');
    
    // Hiệu ứng đếm số
    const duration = 1200;
    const steps = 60;
    const increment = (targetValue - currentValue) / steps;
    let currentStep = 0;
    
    const timer = setInterval(() => {
        currentStep++;
        if (currentStep >= steps) {
            element.textContent = isDecimal ? targetValue.toFixed(2) : targetValue;
            clearInterval(timer);
            
            // Xóa class animation sau khi hoàn thành
            setTimeout(() => {
                element.classList.remove('count-animate');
            }, 300);
        } else {
            const value = currentValue + (increment * currentStep);
            element.textContent = isDecimal ? value.toFixed(2) : Math.round(value);
        }
    }, duration / steps);
}
</script>

<?php include "views/layout/footer.php"; ?>