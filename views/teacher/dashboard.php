<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_teacher.php"; ?>

<div class="main-content">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef;">
        <div>
            <h1 style="font-size: 2rem; margin: 0; color: #2c3e50; font-weight: 600;">
                <i class="fas fa-chalkboard-teacher" style="color: #3498db;"></i> Trang chủ Giáo viên
            </h1>
            <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.95rem;">Quản lý lớp học và thông báo</p>
        </div>
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
            <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y') ?>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="welcome-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px; padding: 35px; margin-bottom: 40px; box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 1;">
            <h2 style="margin: 0 0 10px 0; font-size: 1.8rem; font-weight: 600;">
                Xin chào, <?= htmlspecialchars($user['hoVaTen'] ?? $user['username'] ?? 'Giáo viên') ?>!
            </h2>
            <p style="margin: 0; font-size: 1.05rem; opacity: 0.95; line-height: 1.6;">
                Chúc bạn một ngày giảng dạy thành công và tốt đẹp!
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Lớp chủ nhiệm -->
        <div class="dashboard-card" style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: none; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);">
                    <i class="fas fa-school" style="font-size: 1.5rem; color: white;"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #2c3e50; font-size: 1.4rem; font-weight: 600;">Lớp chủ nhiệm</h3>
                    <p style="margin: 3px 0 0 0; color: #7f8c8d; font-size: 0.9rem;">Thông tin lớp được phân công</p>
                </div>
            </div>
            
            <?php if ($homeroom): ?>
                <div class="homeroom-info" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); border-radius: 15px; padding: 25px; border: 2px solid #e0e0e0; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(102, 126, 234, 0.1); border-radius: 50%;"></div>
                    
                    <div style="position: relative; z-index: 1;">
                        <h4 style="color: #667eea; margin: 0 0 20px 0; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center;">
                            <i class="fas fa-users" style="margin-right: 10px;"></i>
                            <?= htmlspecialchars($homeroom['tenLop']) ?>
                        </h4>
                        
                        <div style="display: grid; gap: 12px;">
                            <div style="display: flex; align-items: center; padding: 12px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-user-graduate" style="color: white; font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #7f8c8d; font-size: 0.85rem;">Sĩ số</div>
                                    <div style="color: #2c3e50; font-weight: 600; font-size: 1.1rem;">6 học sinh</div>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: center; padding: 12px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-layer-group" style="color: white; font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #7f8c8d; font-size: 0.85rem;">Khối</div>
                                    <div style="color: #2c3e50; font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($homeroom['tenKhoi'] ?? 'Chưa xác định') ?></div>
                                </div>
                            </div>
                            
                            <?php if (!empty($homeroom['namHoc'])): ?>
                            <div style="display: flex; align-items: center; padding: 12px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-calendar" style="color: white; font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="color: #7f8c8d; font-size: 0.85rem;">Năm học</div>
                                    <div style="color: #2c3e50; font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($homeroom['namHoc']) ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="index.php?controller=classroom&action=manage&maLop=<?= $homeroom['maLop'] ?>" 
                           class="btn-detail" style="margin-top: 20px; display: inline-flex; align-items: center; padding: 12px 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
                            <i class="fas fa-arrow-right" style="margin-right: 8px;"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="placeholder-content" style="text-align: center; padding: 50px 20px; color: #bdc3c7;">
                    <div style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-users" style="font-size: 2.5rem; color: #e0e0e0;"></i>
                    </div>
                    <p style="margin: 0; font-size: 1.05rem; color: #95a5a6;">Bạn chưa được phân công chủ nhiệm lớp nào</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Thông báo -->
        <div class="dashboard-card" style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: none; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);">
                    <i class="fas fa-bell" style="font-size: 1.5rem; color: white;"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #2c3e50; font-size: 1.4rem; font-weight: 600;">Thông báo mới</h3>
                    <p style="margin: 3px 0 0 0; color: #7f8c8d; font-size: 0.9rem;">Cập nhật thông tin mới nhất</p>
                </div>
            </div>
            
            <?php if (!empty($notifications)): ?>
                <div class="notification-list" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                    <?php foreach ($notifications as $index => $notif): ?>
                        <div class="notification-item" style="padding: 18px; margin-bottom: 15px; background: linear-gradient(135deg, #fff5e6 0%, #ffe6f0 100%); border-radius: 12px; border-left: 4px solid #f093fb; transition: all 0.3s ease; cursor: pointer;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 1.05rem; flex: 1; line-height: 1.4;">
                                    <?= htmlspecialchars($notif['tieuDe']) ?>
                                </div>
                                <div style="width: 8px; height: 8px; background: #f093fb; border-radius: 50%; margin-left: 10px; margin-top: 5px;"></div>
                            </div>
                            <div style="display: flex; align-items: center; font-size: 0.9rem; color: #7f8c8d;">
                                <i class="fas fa-clock" style="margin-right: 6px; color: #f5576c;"></i>
                                <?= date('d/m/Y H:i', strtotime($notif['ngayGui'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="index.php?controller=notification&action=index" 
                   class="btn-detail" style="margin-top: 20px; display: inline-flex; align-items: center; padding: 12px 25px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4); transition: all 0.3s ease;">
                    Xem tất cả <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
            <?php else: ?>
                <div class="placeholder-content" style="text-align: center; padding: 50px 20px; color: #bdc3c7;">
                    <div style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-bell-slash" style="font-size: 2.5rem; color: #e0e0e0;"></i>
                    </div>
                    <p style="margin: 0; font-size: 1.05rem; color: #95a5a6;">Chưa có thông báo mới</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.dashboard-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12) !important;
}

.btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5) !important;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.2);
}

.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.notification-list::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 10px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
}

.welcome-card {
    animation: slideInDown 0.8s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?php include "views/layout/footer.php"; ?>