<?php 
// views/teacher/assignment/index.php

include 'views/layout/header.php'; 
include 'views/layout/sidebar_teacher.php'; 
?>

<style>
/* Main Container */
.main-content {
    padding: 30px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Header Section */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.header-title h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-title h2 i {
    color: #3498db;
}

.header-title p {
    color: #7f8c8d;
    margin: 8px 0 0 0;
    font-size: 15px;
}

.btn-create-new {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 28px;
    border-radius: 10px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-create-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    color: white;
}

/* Alert Messages */
.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-error {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Assignment Cards Grid */
.assignments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 25px;
    margin-top: 25px;
}

/* Assignment Card */
.assignment-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.assignment-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 35px rgba(0,0,0,0.15);
}

.card-content {
    padding: 25px;
}

/* Card Header */
.card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    gap: 15px;
}

.card-title {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 700;
    flex: 1;
    line-height: 1.4;
    display: flex;
    align-items: start;
    gap: 10px;
}

.card-title i {
    color: #667eea;
    margin-top: 3px;
}

/* Status Badge */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.status-open {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.status-closed {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

/* Card Description */
.card-description {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 20px;
    line-height: 1.6;
    min-height: 60px;
}

/* Card Details */
.card-details {
    margin-bottom: 20px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 12px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.icon-subject {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.icon-class {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.icon-deadline {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
}

.icon-date {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.detail-text {
    font-size: 13px;
    color: #495057;
    flex: 1;
}

.detail-text strong {
    color: #2c3e50;
    font-weight: 600;
}

/* Card Actions */
.card-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.btn-view-submissions {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-view-submissions:hover {
    transform: translateX(3px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: white;
    font-size: 14px;
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}

.btn-edit:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.btn-delete:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    background: white;
    padding: 60px 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 64px;
    color: #17a2b8;
    margin-bottom: 20px;
}

.empty-state p {
    color: #6c757d;
    font-size: 16px;
    margin-bottom: 20px;
}

.empty-state a {
    color: #667eea;
    font-weight: 700;
    text-decoration: none;
    font-size: 16px;
}

.empty-state a:hover {
    text-decoration: underline;
}

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: white;
    padding: 35px;
    border-radius: 15px;
    max-width: 550px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.modal-header i {
    font-size: 38px;
    color: #dc3545;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
    font-weight: 700;
}

.modal-body p {
    color: #495057;
    margin-bottom: 15px;
    line-height: 1.6;
    font-size: 15px;
}

.modal-body .warning-text {
    color: #dc3545;
    font-weight: 700;
    font-size: 16px;
    margin: 15px 0 10px 0;
}

.modal-body ul {
    color: #6c757d;
    margin: 0 0 20px 25px;
    line-height: 1.8;
}

.modal-body ul li {
    margin-bottom: 8px;
}

.modal-body ul li strong {
    color: #dc3545;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 25px;
}

.btn-modal {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 15px;
}

.btn-cancel {
    background: #e9ecef;
    color: #495057;
}

.btn-cancel:hover {
    background: #dee2e6;
    transform: translateY(-1px);
}

.btn-confirm {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
    .assignments-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .btn-create-new {
        width: 100%;
        justify-content: center;
    }
}
</style>

<main class="main-content">
    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <h2>
                <i class="fas fa-clipboard-check"></i>
                Quản lý Bài tập/Kiểm tra
            </h2>
            <p>Tạo, chỉnh sửa và chấm điểm bài tập cho học sinh</p>
        </div>
        <div>
            <a href="index.php?controller=assignmentTeacher&action=create" class="btn-create-new">
                <i class="fas fa-plus-circle"></i>
                Tạo bài tập mới
            </a>
        </div>
    </div>

    <!-- Thông báo -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Danh sách bài tập -->
    <div class="assignments-grid">
        <?php if(isset($baiTapList) && is_array($baiTapList) && count($baiTapList) > 0): ?>
            <?php foreach($baiTapList as $baiTap): ?>
                <div class="assignment-card">
                    <div class="card-content">
                        <!-- Card Header -->
                        <div class="card-header-custom">
                            <h5 class="card-title">
                                <i class="fas fa-file-alt"></i>
                                <span><?php echo htmlspecialchars($baiTap['tenBaiTap']); ?></span>
                            </h5>
                            <?php if($baiTap['trangThai'] == 'DangMo'): ?>
                                <span class="status-badge status-open">Đang mở</span>
                            <?php else: ?>
                                <span class="status-badge status-closed">Đã đóng</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Description -->
                        <p class="card-description">
                            <?php echo htmlspecialchars(substr($baiTap['moTa'] ?? 'Không có mô tả', 0, 100)); ?>
                            <?php if(strlen($baiTap['moTa'] ?? '') > 100) echo '...'; ?>
                        </p>
                        
                        <!-- Details -->
                        <div class="card-details">
                            <div class="detail-item">
                                <div class="detail-icon icon-subject">
                                    <i class="fas fa-book"></i>
                                </div>
                                <span class="detail-text">
                                    <strong>Môn học:</strong> <?php echo htmlspecialchars($baiTap['tenMon']); ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <div class="detail-icon icon-class">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span class="detail-text">
                                    <strong>Lớp:</strong> <?php echo htmlspecialchars($baiTap['tenLop']); ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <div class="detail-icon icon-deadline">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <span class="detail-text">
                                    <strong>Hạn nộp:</strong> <?php echo date('d/m/Y H:i', strtotime($baiTap['thoiHanNop'])); ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <div class="detail-icon icon-date">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span class="detail-text">
                                    <strong>Ngày tạo:</strong> <?php echo date('d/m/Y', strtotime($baiTap['ngayTao'])); ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="card-actions">
                            <a href="index.php?controller=assignmentTeacher&action=danhsachbainop&id=<?php echo $baiTap['maBaiTap']; ?>" 
                               class="btn-view-submissions">
                                <i class="fas fa-list-check"></i>
                                Xem bài nộp
                            </a>
                            
                            <div class="action-buttons">
                                <a href="index.php?controller=assignmentTeacher&action=edit&id=<?php echo $baiTap['maBaiTap']; ?>" 
                                   class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="xoaBaiTap(<?php echo $baiTap['maBaiTap']; ?>)" 
                                        class="btn-action btn-delete" title="Xóa vĩnh viễn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard"></i>
                <p>Chưa có bài tập nào được tạo</p>
                <a href="index.php?controller=assignmentTeacher&action=create">
                    <i class="fas fa-plus-circle"></i> Tạo bài tập đầu tiên của bạn
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal xác nhận xóa -->
<div id="confirmDeleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>⚠️ Xác nhận xóa bài tập</h3>
        </div>
        <div class="modal-body">
            <p style="font-size: 16px;"><strong>Bạn có chắc chắn muốn xóa bài tập này không?</strong></p>
            
            <p class="warning-text">⚠️ CẢNH BÁO: Hành động này sẽ:</p>
            <ul>
                <li>🗑️ Xóa <strong>vĩnh viễn</strong> bài tập khỏi hệ thống</li>
                <li>📝 Xóa <strong>tất cả bài nộp</strong> của học sinh</li>
                <li>📊 Xóa <strong>tất cả điểm số</strong> đã chấm</li>
                <li>📎 Xóa <strong>tất cả file đính kèm</strong></li>
                <li>❌ <strong>KHÔNG THỂ KHÔI PHỤC</strong> dữ liệu</li>
            </ul>
            
            <p style="background: #fff3cd; padding: 12px; border-radius: 8px; border-left: 4px solid #ffc107; margin-top: 15px;">
                <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Nếu chỉ muốn tạm đóng bài tập, hãy sử dụng chức năng "Chỉnh sửa" để thay đổi trạng thái.
            </p>
        </div>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-modal btn-cancel">
                <i class="fas fa-times"></i> Hủy bỏ
            </button>
            <button id="btnConfirmDelete" class="btn-modal btn-confirm">
                <i class="fas fa-trash-alt"></i> Xóa vĩnh viễn
            </button>
        </div>
    </div>
</div>

<script>
let maBaiTapToDelete = null;

function xoaBaiTap(maBaiTap) {
    maBaiTapToDelete = maBaiTap;
    document.getElementById('confirmDeleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('confirmDeleteModal').style.display = 'none';
    maBaiTapToDelete = null;
}

document.getElementById('btnConfirmDelete').addEventListener('click', function() {
    if(maBaiTapToDelete) {
        // Hiển thị loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
        this.disabled = true;
        
        fetch('index.php?controller=assignmentTeacher&action=delete&id=' + maBaiTapToDelete, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Đóng modal
                closeDeleteModal();
                
                // Hiển thị thông báo thành công
                alert('✅ ' + data.message);
                
                // Reload trang để cập nhật danh sách
                window.location.reload();
            } else {
                alert('❌ ' + (data.message || 'Có lỗi xảy ra khi xóa bài tập'));
                
                // Reset button
                this.innerHTML = '<i class="fas fa-trash-alt"></i> Xóa vĩnh viễn';
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Có lỗi xảy ra khi xóa bài tập. Vui lòng thử lại!');
            
            // Reset button
            this.innerHTML = '<i class="fas fa-trash-alt"></i> Xóa vĩnh viễn';
            this.disabled = false;
        });
    }
});

// Đóng modal khi click bên ngoài
document.getElementById('confirmDeleteModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeDeleteModal();
    }
});

// Đóng modal bằng phím ESC
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>