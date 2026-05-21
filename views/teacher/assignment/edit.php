<?php 
// views/teacher/assignment/edit.php
include 'views/layout/header.php'; 
include 'views/layout/sidebar_teacher.php'; 
?>

<main class="main-content">
    <div class="py-4">
        <nav style="margin-bottom: 20px;">
            <a href="index.php?controller=assignmentTeacher&action=index" style="color: #3498db; text-decoration: none;">Bài tập</a>
            <span style="color: #7f8c8d;"> / </span>
            <span style="color: #7f8c8d;">Chỉnh sửa</span>
        </nav>
        
        <h2 style="margin-bottom: 20px; color: #2c3e50;">
            <i class="fas fa-edit"></i> Chỉnh sửa bài tập/kiểm tra
        </h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <form action="index.php?controller=assignmentTeacher&action=update" method="POST" id="formEditAssignment">
                <input type="hidden" name="maBaiTap" value="<?php echo $baiTap['maBaiTap']; ?>">
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <!-- Tên bài tập -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Tên bài tập/kiểm tra <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="tenBaiTap" id="tenBaiTap" required
                               value="<?php echo htmlspecialchars($baiTap['tenBaiTap']); ?>"
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Lớp (read-only) -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Lớp
                            </label>
                            <input type="text" readonly
                                   value="<?php echo htmlspecialchars($baiTap['tenLop']); ?>"
                                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background: #e9ecef;">
                            <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Không thể thay đổi lớp sau khi tạo
                            </small>
                        </div>

                        <!-- Môn học (read-only) -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Môn học
                            </label>
                            <input type="text" readonly
                                   value="<?php echo htmlspecialchars($baiTap['tenMon']); ?>"
                                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; background: #e9ecef;">
                            <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Không thể thay đổi môn sau khi tạo
                            </small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Thời hạn nộp -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Thời hạn nộp <span style="color: red;">*</span>
                            </label>
                            <input type="datetime-local" name="thoiHanNop" id="thoiHanNop" required
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($baiTap['thoiHanNop'])); ?>"
                                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                        </div>

                        <!-- Trạng thái -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Trạng thái <span style="color: red;">*</span>
                            </label>
                            <select name="trangThai" id="trangThai" required
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                <option value="DangMo" <?php echo $baiTap['trangThai'] == 'DangMo' ? 'selected' : ''; ?>>Đang mở</option>
                                <option value="DaDong" <?php echo $baiTap['trangThai'] == 'DaDong' ? 'selected' : ''; ?>>Đã đóng</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Mô tả ngắn
                        </label>
                        <textarea name="moTa" id="moTa" rows="2"
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"><?php echo htmlspecialchars($baiTap['moTa'] ?? ''); ?></textarea>
                    </div>

                    <!-- Nội dung -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Nội dung chi tiết
                        </label>
                        <textarea name="noiDung" id="noiDung" rows="5"
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"><?php echo htmlspecialchars($baiTap['noiDung'] ?? ''); ?></textarea>
                    </div>

                    <!-- Thông tin tạo -->
                    <div style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; border-left: 4px solid #17a2b8;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($baiTap['ngayTao'])); ?>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; justify-content: space-between; padding-top: 20px; border-top: 1px solid #e9ecef;">
                        <a href="index.php?controller=assignmentTeacher&action=index" 
                           style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Form validation
document.getElementById('formEditAssignment').addEventListener('submit', function(e) {
    const tenBaiTap = document.getElementById('tenBaiTap').value.trim();
    const thoiHanNop = document.getElementById('thoiHanNop').value;
    
    if(!tenBaiTap) {
        e.preventDefault();
        alert('Vui lòng nhập tên bài tập');
        return false;
    }
    
    if(!thoiHanNop) {
        e.preventDefault();
        alert('Vui lòng chọn thời hạn nộp');
        return false;
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>