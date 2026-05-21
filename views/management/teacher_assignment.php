<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<?php 
// Tính năm học hiện tại
$currentYear = date('Y');
$currentMonth = date('n');
if ($currentMonth >= 8) {
    $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
} else {
    $currentSchoolYear = ($currentYear - 1) . '-' . $currentYear;
}
?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">👨‍🏫 Phân công giáo viên</div>
    </div>

    <!-- Thanh công cụ chọn loại phân công -->
    <div class="card" style="margin-bottom:15px;">
        <div class="card-header">
            <strong>Loại phân công</strong>
        </div>
        <div class="card-body" style="padding: 15px;">
            <div class="form-row">
                <div class="form-col">
                    <a href="index.php?controller=teacherAssignment&action=index&type=chunhiem" 
                       class="btn <?= ($type==='chunhiem')?'btn-warning':'btn-outline' ?>">
                        👨‍🏫 Giáo viên chủ nhiệm
                    </a>
                    <a href="index.php?controller=teacherAssignment&action=index&type=bomon" 
                       class="btn <?= ($type==='bomon')?'btn-warning':'btn-outline' ?>">
                        📚 Giáo viên bộ môn
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Form phân công -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <strong><?= ($type === 'chunhiem') ? 'Phân công giáo viên chủ nhiệm' : 'Phân công giáo viên bộ môn' ?></strong>
        </div>
        <div class="card-body">
            <?php if ($type === 'chunhiem'): ?>
                <!-- Form phân công chủ nhiệm -->
                <form method="POST" action="index.php?controller=teacherAssignment&action=assignHomeRoomTeacher">
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Giáo viên</label>
                            <select name="maGV" class="form-control" required>
                                <option value="">-- Chọn giáo viên --</option>
                                <?php foreach($teachers as $teacher): ?>
                                    <option value="<?= htmlspecialchars($teacher['maGV']) ?>">
                                        <?= htmlspecialchars($teacher['hoVaTen']) ?> - <?= htmlspecialchars($teacher['monGiangDay'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($teachers)): ?>
                                <small class="text-muted" style="color: #dc3545;">⚠️ Tất cả giáo viên đã được phân công chủ nhiệm</small>
                            <?php endif; ?>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Lớp học</label>
                            <select name="maLop" class="form-control" required>
                                <option value="">-- Chọn lớp --</option>
                                <?php foreach($classes as $class): ?>
                                    <option value="<?= htmlspecialchars($class['maLop']) ?>">
                                        <?= htmlspecialchars($class['tenLop']) ?> - <?= htmlspecialchars($class['tenKhoi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($classes)): ?>
                                <small class="text-muted" style="color: #28a745;">✅ Tất cả lớp đã có giáo viên chủ nhiệm</small>
                            <?php endif; ?>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Năm học</label>
                            <input type="text" name="namHoc" class="form-control" value="<?= $currentSchoolYear ?>" readonly required>
                    
                        </div>
                    </div>
                    <div class="form-row" style="margin-top:15px;">
                        <div class="form-col">
                            <button type="submit" class="btn btn-primary" 
                                <?= (empty($teachers) || empty($classes)) ? 'disabled' : '' ?>>
                                💾 Lưu phân công
                            </button>
                            <?php if (empty($teachers) || empty($classes)): ?>
                                <small class="text-muted" style="display: block; margin-top: 5px; color: #dc3545;">
                                    ⚠️ Không thể phân công khi thiếu giáo viên hoặc lớp
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <!-- Form phân công bộ môn -->
                <form method="POST" action="index.php?controller=teacherAssignment&action=assignSubjectTeacher">
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Lớp học</label>
                            <select name="maLop" id="maLop" class="form-control" required>
                                <option value="">-- Chọn lớp --</option>
                                <?php foreach($classes as $class): ?>
                                    <option value="<?= htmlspecialchars($class['maLop']) ?>">
                                        <?= htmlspecialchars($class['tenLop']) ?> - <?= htmlspecialchars($class['tenKhoi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Môn học</label>
                            <select name="maMon" id="maMon" class="form-control" required>
                                <option value="">-- Chọn môn học --</option>
                                <?php foreach($subjects as $subject): ?>
                                    <option value="<?= htmlspecialchars($subject['maMon']) ?>">
                                        <?= htmlspecialchars($subject['tenMon']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Giáo viên</label>
                            <select name="maGV" id="maGV" class="form-control" required>
                                <option value="">-- Chọn môn học trước --</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Năm học</label>
                            <input type="text" name="namHoc" class="form-control" value="<?= $currentSchoolYear ?>" readonly required>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top:15px;">
                        <div class="form-col">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>💾 Lưu phân công</button>
                            <small class="text-muted" style="display: block; margin-top: 5px;">
                                ℹ️ Chọn lớp → môn học → giáo viên để kích hoạt nút lưu
                            </small>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Danh sách phân công hiện tại -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin:0;font-size:16px;">
                <?= ($type === 'chunhiem') ? 'Danh sách giáo viên chủ nhiệm' : 'Danh sách giáo viên bộ môn' ?>
            </h3>
        </div>
        <div class="card-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <?php if ($type === 'chunhiem'): ?>
                            <th>Giáo viên</th>
                            <th>Lớp</th>
                            <th>Khối</th>
                            <th>Năm học</th>
                            <th>Thao tác</th>
                        <?php else: ?>
                            <th>Giáo viên</th>
                            <th>Lớp</th>
                            <th>Môn học</th>
                            <th>Năm học</th>
                            <th>Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($currentAssignments)): ?>
                        <?php foreach($currentAssignments as $assignment): ?>
                            <tr>
                                <td><?= htmlspecialchars($assignment['tenGV']) ?></td>
                                <td><?= htmlspecialchars($assignment['tenLop']) ?></td>
                                <?php if ($type === 'chunhiem'): ?>
                                    <td><?= htmlspecialchars($assignment['tenKhoi']) ?></td>
                                    <td><?= htmlspecialchars($assignment['namHoc']) ?></td>
                                    <td>
                                        <form method="POST" action="index.php?controller=teacherAssignment&action=cancelHomeRoomTeacher" style="display:inline;">
                                            <input type="hidden" name="maPCGVCN" value="<?= $assignment['maPCGVCN'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn hủy phân công này?')">
                                                ❌ Hủy
                                            </button>
                                        </form>
                                    </td>
                                <?php else: ?>
                                    <td><?= htmlspecialchars($assignment['tenMon']) ?></td>
                                    <td><?= htmlspecialchars($assignment['namHoc']) ?></td>
                                    <td>
                                        <form method="POST" action="index.php?controller=teacherAssignment&action=cancelSubjectTeacher" style="display:inline;">
                                            <input type="hidden" name="maPCGVBM" value="<?= $assignment['maPCGVBM'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn hủy phân công này?')">
                                                ❌ Hủy
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Chưa có phân công nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lopSelect = document.querySelector('#maLop');
    const monSelect = document.querySelector('#maMon');
    const gvSelect = document.querySelector('#maGV');
    const submitBtn = document.querySelector('#submitBtn');
    
    // Chỉ chạy script cho form phân công bộ môn
    if (!lopSelect || !monSelect || !gvSelect) {
        return;
    }

    // Reset trạng thái ban đầu
    monSelect.innerHTML = '<option value="">-- Chọn lớp trước --</option>';
    monSelect.disabled = true;
    gvSelect.innerHTML = '<option value="">-- Chọn môn học trước --</option>';
    gvSelect.disabled = true;
    if (submitBtn) submitBtn.disabled = true;

    // Load môn học theo lớp
    lopSelect.addEventListener('change', function() {
        const maLop = this.value;
        
        // Reset các select phụ thuộc
        monSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        monSelect.disabled = true;
        gvSelect.innerHTML = '<option value="">-- Chọn môn học trước --</option>';
        gvSelect.disabled = true;
        if (submitBtn) submitBtn.disabled = true;
        
        if (maLop) {
            // Gọi AJAX để lấy danh sách môn học chưa phân công
            fetch('index.php?controller=teacherAssignment&action=getSubjectsByClass', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'maLop=' + encodeURIComponent(maLop)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(subjects => {
                monSelect.innerHTML = '<option value="">-- Chọn môn học --</option>';
                monSelect.disabled = false;
                
                if (subjects && subjects.length > 0) {
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.maMon;
                        option.textContent = subject.tenMon;
                        monSelect.appendChild(option);
                    });
                } else {
                    monSelect.innerHTML = '<option value="">-- Lớp đã có đủ giáo viên bộ môn --</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                monSelect.innerHTML = '<option value="">-- Lỗi tải danh sách môn học --</option>';
            });
        } else {
            monSelect.innerHTML = '<option value="">-- Chọn lớp trước --</option>';
            monSelect.disabled = true;
            gvSelect.innerHTML = '<option value="">-- Chọn môn học trước --</option>';
            gvSelect.disabled = true;
        }
    });
    
    // Load giáo viên theo môn học
    monSelect.addEventListener('change', function() {
        const maMon = this.value;
        
        if (maMon && maMon !== '') {
            gvSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            gvSelect.disabled = true;
            if (submitBtn) submitBtn.disabled = true;
            
            fetch('index.php?controller=teacherAssignment&action=getTeachersBySubject', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'maMon=' + encodeURIComponent(maMon)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(teachers => {
                gvSelect.innerHTML = '<option value="">-- Chọn giáo viên --</option>';
                gvSelect.disabled = false;
                
                if (teachers && teachers.length > 0) {
                    teachers.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.maGV;
                        option.textContent = teacher.hoVaTen + (teacher.monGiangDay ? ' - ' + teacher.monGiangDay : '');
                        gvSelect.appendChild(option);
                    });
                } else {
                    gvSelect.innerHTML = '<option value="">-- Không có giáo viên dạy môn này --</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                gvSelect.innerHTML = '<option value="">-- Lỗi tải danh sách giáo viên --</option>';
            });
        } else {
            gvSelect.innerHTML = '<option value="">-- Chọn môn học trước --</option>';
            gvSelect.disabled = true;
            if (submitBtn) submitBtn.disabled = true;
        }
    });

    // Kích hoạt nút submit khi có đủ thông tin
    if (gvSelect && submitBtn) {
        gvSelect.addEventListener('change', function() {
            const maLop = lopSelect.value;
            const maMon = monSelect.value;
            const maGV = this.value;
            
            submitBtn.disabled = !(maLop && maMon && maGV);
        });
    }
});
</script>

<?php include "views/layout/footer.php"; ?>