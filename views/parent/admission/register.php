<?php
$current_page = 'register';
include __DIR__ . '/../../layout/header.php';
include __DIR__ . '/../../layout/sidebar_parent.php';
?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">📝 Đăng ký tuyển sinh</div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($kyTuyenSinhList)): ?>
        
        <!-- Form chọn kỳ tuyển sinh -->
        <div class="form-section">
            <h3>📅 Chọn kỳ tuyển sinh</h3>
            <form method="GET" action="" id="kyTuyenSinhForm">
                <input type="hidden" name="controller" value="admission">
                <input type="hidden" name="action" value="register">
                
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Kỳ tuyển sinh</label>
                        <select name="maKyTS" class="form-control" onchange="document.getElementById('kyTuyenSinhForm').submit()" required>
                            <option value="">-- Chọn kỳ tuyển sinh --</option>
                            <?php foreach($kyTuyenSinhList as $ky): 
                                $status_icon = $ky['trangThai'] == 'DangMo' ? '🟢' : '🔴';
                                $status_text = $ky['trangThai'] == 'DangMo' ? 'Đang mở' : 'Đã đóng';
                            ?>
                                <option value="<?= $ky['maKyTS'] ?>" 
                                    <?= ($selectedKyTS && $ky['maKyTS'] == $selectedKyTS['maKyTS']) ? 'selected' : '' ?>>
                                    <?= $status_icon ?> <?= htmlspecialchars($ky['tenKyTS']) ?> 
                                    (<?= date('d/m/Y', strtotime($ky['ngayBatDau'])) ?> - <?= date('d/m/Y', strtotime($ky['ngayKetThuc'])) ?>)
                                    - <?= $status_text ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($selectedKyTS): ?>
            <!-- Hiển thị thông tin kỳ đã chọn -->
            <div class="alert <?= $selectedKyTS['trangThai'] == 'DangMo' ? 'alert-success' : 'alert-warning' ?>">
                <h4>📋 Thông tin kỳ tuyển sinh</h4>
                <p><strong>Tên kỳ:</strong> <?= htmlspecialchars($selectedKyTS['tenKyTS']) ?></p>
                <p><strong>Năm học:</strong> <?= htmlspecialchars($selectedKyTS['namHoc']) ?></p>
                <p><strong>Thời gian:</strong> <?= date('d/m/Y', strtotime($selectedKyTS['ngayBatDau'])) ?> - <?= date('d/m/Y', strtotime($selectedKyTS['ngayKetThuc'])) ?></p>
                <p><strong>Trạng thái:</strong> 
                    <span class="status-badge <?= $selectedKyTS['trangThai'] == 'DangMo' ? 'status-success' : 'status-danger' ?>">
                        <?= $selectedKyTS['trangThai'] == 'DangMo' ? '🟢 Đang mở nhận hồ sơ' : '🔴 Đã đóng' ?>
                    </span>
                </p>
                
                <?php if ($selectedKyTS['trangThai'] == 'DangMo'): ?>
                    <p class="mt-2"><strong>💡 Hướng dẫn:</strong> Bạn có thể điền form đăng ký bên dưới.</p>
                <?php else: ?>
                    <p class="mt-2"><strong>💡 Thông báo:</strong> Kỳ tuyển sinh này đã đóng. Vui lòng chọn kỳ khác hoặc liên hệ quản trị viên.</p>
                <?php endif; ?>
            </div>

            <!-- CHỈ HIỂN THỊ FORM KHI KỲ ĐANG MỞ -->
            <?php if ($selectedKyTS['trangThai'] == 'DangMo'): ?>
                
                <form method="POST" action="index.php?controller=admission&action=register" id="admissionForm">
                    <input type="hidden" name="maKyTS" value="<?= $selectedKyTS['maKyTS'] ?>">

                    <!-- Thông tin học sinh -->
                    <div class="form-section">
                        <h3>👨‍🎓 Thông tin học sinh</h3>
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Họ và tên học sinh *</label>
                                <input type="text" name="hoTenHS" class="form-control" value="<?= $_POST['hoTenHS'] ?? '' ?>" required 
                                       pattern="[a-zA-ZÀ-ỹ\s]+" title="Họ tên chỉ được chứa chữ cái">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Giới tính *</label>
                                <select name="gioiTinhHS" class="form-control" required>
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" <?= ($_POST['gioiTinhHS'] ?? '') == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                    <option value="Nữ" <?= ($_POST['gioiTinhHS'] ?? '') == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Ngày sinh *</label>
                                <input type="date" name="ngaySinhHS" class="form-control" value="<?= $_POST['ngaySinhHS'] ?? '' ?>" required 
                                       max="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="soDienThoaiHS" class="form-control" value="<?= $_POST['soDienThoaiHS'] ?? '' ?>"
                                       pattern="(0[3|5|7|8|9])[0-9]{8}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Email</label>
                                <input type="email" name="emailHS" class="form-control" value="<?= $_POST['emailHS'] ?? '' ?>">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Địa chỉ *</label>
                                <input type="text" name="diaChiHS" class="form-control" value="<?= $_POST['diaChiHS'] ?? '' ?>" required>
                            </div>
                        </div>

                        <!-- THÊM TÔN GIÁO VÀ DÂN TỘC -->
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Dân tộc *</label>
                                <select name="danToc" class="form-control" required>
                                    <option value="">-- Chọn dân tộc --</option>
                                    <option value="Kinh" <?= ($_POST['danToc'] ?? '') == 'Kinh' ? 'selected' : '' ?>>Kinh</option>
                                    <option value="Tày" <?= ($_POST['danToc'] ?? '') == 'Tày' ? 'selected' : '' ?>>Tày</option>
                                    <option value="Thái" <?= ($_POST['danToc'] ?? '') == 'Thái' ? 'selected' : '' ?>>Thái</option>
                                    <option value="Hoa" <?= ($_POST['danToc'] ?? '') == 'Hoa' ? 'selected' : '' ?>>Hoa</option>
                                    <option value="Khmer" <?= ($_POST['danToc'] ?? '') == 'Khmer' ? 'selected' : '' ?>>Khmer</option>
                                    <option value="Mường" <?= ($_POST['danToc'] ?? '') == 'Mường' ? 'selected' : '' ?>>Mường</option>
                                    <option value="Nùng" <?= ($_POST['danToc'] ?? '') == 'Nùng' ? 'selected' : '' ?>>Nùng</option>
                                    <option value="HMông" <?= ($_POST['danToc'] ?? '') == 'HMông' ? 'selected' : '' ?>>H'Mông</option>
                                    <option value="Dao" <?= ($_POST['danToc'] ?? '') == 'Dao' ? 'selected' : '' ?>>Dao</option>
                                    <option value="Gia Rai" <?= ($_POST['danToc'] ?? '') == 'Gia Rai' ? 'selected' : '' ?>>Gia Rai</option>
                                    <option value="Ê Đê" <?= ($_POST['danToc'] ?? '') == 'Ê Đê' ? 'selected' : '' ?>>Ê Đê</option>
                                    <option value="Ba Na" <?= ($_POST['danToc'] ?? '') == 'Ba Na' ? 'selected' : '' ?>>Ba Na</option>
                                    <option value="Xơ Đăng" <?= ($_POST['danToc'] ?? '') == 'Xơ Đăng' ? 'selected' : '' ?>>Xơ Đăng</option>
                                    <option value="Sán Chay" <?= ($_POST['danToc'] ?? '') == 'Sán Chay' ? 'selected' : '' ?>>Sán Chay</option>
                                    <option value="Cơ Ho" <?= ($_POST['danToc'] ?? '') == 'Cơ Ho' ? 'selected' : '' ?>>Cơ Ho</option>
                                    <option value="Chăm" <?= ($_POST['danToc'] ?? '') == 'Chăm' ? 'selected' : '' ?>>Chăm</option>
                                    <option value="Sán Dìu" <?= ($_POST['danToc'] ?? '') == 'Sán Dìu' ? 'selected' : '' ?>>Sán Dìu</option>
                                    <option value="Hrê" <?= ($_POST['danToc'] ?? '') == 'Hrê' ? 'selected' : '' ?>>Hrê</option>
                                    <option value="Mnông" <?= ($_POST['danToc'] ?? '') == 'Mnông' ? 'selected' : '' ?>>Mnông</option>
                                    <option value="Ra Glai" <?= ($_POST['danToc'] ?? '') == 'Ra Glai' ? 'selected' : '' ?>>Ra Glai</option>
                                    <option value="Xtiêng" <?= ($_POST['danToc'] ?? '') == 'Xtiêng' ? 'selected' : '' ?>>Xtiêng</option>
                                    <option value="Bru-Vân Kiều" <?= ($_POST['danToc'] ?? '') == 'Bru-Vân Kiều' ? 'selected' : '' ?>>Bru-Vân Kiều</option>
                                    <option value="Thổ" <?= ($_POST['danToc'] ?? '') == 'Thổ' ? 'selected' : '' ?>>Thổ</option>
                                    <option value="Giáy" <?= ($_POST['danToc'] ?? '') == 'Giáy' ? 'selected' : '' ?>>Giáy</option>
                                    <option value="Cơ Tu" <?= ($_POST['danToc'] ?? '') == 'Cơ Tu' ? 'selected' : '' ?>>Cơ Tu</option>
                                    <option value="Gié Triêng" <?= ($_POST['danToc'] ?? '') == 'Gié Triêng' ? 'selected' : '' ?>>Gié Triêng</option>
                                    <option value="Mạ" <?= ($_POST['danToc'] ?? '') == 'Mạ' ? 'selected' : '' ?>>Mạ</option>
                                    <option value="Khơ Mú" <?= ($_POST['danToc'] ?? '') == 'Khơ Mú' ? 'selected' : '' ?>>Khơ Mú</option>
                                    <option value="Kháng" <?= ($_POST['danToc'] ?? '') == 'Kháng' ? 'selected' : '' ?>>Kháng</option>
                                    <option value="Khác" <?= ($_POST['danToc'] ?? '') == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>
                            <div class="form-col">
                                <label class="form-label">Tôn giáo</label>
                                <select name="tonGiao" class="form-control">
                                    <option value="">-- Chọn tôn giáo --</option>
                                    <option value="Không" <?= ($_POST['tonGiao'] ?? '') == 'Không' ? 'selected' : '' ?>>Không</option>
                                    <option value="Phật giáo" <?= ($_POST['tonGiao'] ?? '') == 'Phật giáo' ? 'selected' : '' ?>>Phật giáo</option>
                                    <option value="Công giáo" <?= ($_POST['tonGiao'] ?? '') == 'Công giáo' ? 'selected' : '' ?>>Công giáo</option>
                                    <option value="Tin Lành" <?= ($_POST['tonGiao'] ?? '') == 'Tin Lành' ? 'selected' : '' ?>>Tin Lành</option>
                                    <option value="Cao Đài" <?= ($_POST['tonGiao'] ?? '') == 'Cao Đài' ? 'selected' : '' ?>>Cao Đài</option>
                                    <option value="Hòa Hảo" <?= ($_POST['tonGiao'] ?? '') == 'Hòa Hảo' ? 'selected' : '' ?>>Phật giáo Hòa Hảo</option>
                                    <option value="Hồi giáo" <?= ($_POST['tonGiao'] ?? '') == 'Hồi giáo' ? 'selected' : '' ?>>Hồi giáo</option>
                                    <option value="Tôn giáo khác" <?= ($_POST['tonGiao'] ?? '') == 'Tôn giáo khác' ? 'selected' : '' ?>>Tôn giáo khác</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin phụ huynh -->
                    <div class="form-section">
                        <h3>👨‍👩‍👧‍👦 Thông tin phụ huynh</h3>
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Họ và tên phụ huynh *</label>
                                <input type="text" name="hoTenPH" class="form-control" value="<?= $_POST['hoTenPH'] ?? '' ?>" required
                                       pattern="[a-zA-ZÀ-ỹ\s]+" title="Họ tên chỉ được chứa chữ cái">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Quan hệ với học sinh *</label>
                                <select name="quanHe" class="form-control" required>
                                    <option value="">-- Chọn quan hệ --</option>
                                    <option value="Bố" <?= ($_POST['quanHe'] ?? '') == 'Bố' ? 'selected' : '' ?>>Bố</option>
                                    <option value="Mẹ" <?= ($_POST['quanHe'] ?? '') == 'Mẹ' ? 'selected' : '' ?>>Mẹ</option>
                                    <option value="Người giám hộ" <?= ($_POST['quanHe'] ?? '') == 'Người giám hộ' ? 'selected' : '' ?>>Người giám hộ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Số điện thoại phụ huynh *</label>
                                <input type="tel" name="soDienThoaiPH" class="form-control" value="<?= $_POST['soDienThoaiPH'] ?? '' ?>" required
                                       pattern="(0[3|5|7|8|9])[0-9]{8}" title="Số điện thoại phải có 10 số và bắt đầu bằng 03, 05, 07, 08, 09">
                            </div>
                            <div class="form-col">
                                <label class="form-label">Email phụ huynh</label>
                                <input type="email" name="emailPH" class="form-control" value="<?= $_POST['emailPH'] ?? '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Nguyện vọng -->
                    <div class="form-section">
                        <h3>🎯 Nguyện vọng tuyển sinh lớp 10</h3>
                        <div class="grade-info">
                            <strong>📚 Tuyển sinh Lớp 10:</strong> Mỗi nguyện vọng cần chọn <strong>trường</strong> và <strong>môn chuyên</strong>. Môn tự chọn là tuỳ chọn.
                        </div>
                        
                        <div id="nguyenVongContainer">
                            <?php for($i = 0; $i < 3; $i++): ?>
                            <div class="nguyen-vong-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                                <h4>Nguyện vọng <?= $i + 1 ?></h4>
                                <div class="form-row">
                                    <div class="form-col">
                                        <label class="form-label">Trường *</label>
                                        <select name="nguyenVong[<?= $i ?>][truong]" class="form-control truong-select" required>
                                            <option value="">-- Chọn trường --</option>
                                            <?php foreach($truongList as $truong): ?>
                                                <option value="<?= $truong['maTruong'] ?>" <?= ($_POST['nguyenVong'][$i]['truong'] ?? '') == $truong['maTruong'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($truong['tenTruong']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-col">
                                        <label class="form-label">Môn chuyên *</label>
                                        <select name="nguyenVong[<?= $i ?>][monChuyen]" class="form-control mon-chuyen-select" required>
                                            <option value="">-- Chọn môn chuyên --</option>
                                            <option value="TOAN" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'TOAN' ? 'selected' : '' ?>>Toán</option>
                                            <option value="VAN" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'VAN' ? 'selected' : '' ?>>Ngữ Văn</option>
                                            <option value="ANH" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'ANH' ? 'selected' : '' ?>>Tiếng Anh</option>
                                            <option value="LY" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'LY' ? 'selected' : '' ?>>Vật Lý</option>
                                            <option value="HOA" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'HOA' ? 'selected' : '' ?>>Hóa Học</option>
                                            <option value="SINH" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'SINH' ? 'selected' : '' ?>>Sinh Học</option>
                                            <option value="SU" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'SU' ? 'selected' : '' ?>>Lịch Sử</option>
                                            <option value="DIA" <?= ($_POST['nguyenVong'][$i]['monChuyen'] ?? '') == 'DIA' ? 'selected' : '' ?>>Địa Lý</option>
                                        </select>
                                    </div>
                                    <div class="form-col">
                                        <label class="form-label">Môn tự chọn</label>
                                        <select name="nguyenVong[<?= $i ?>][monTuChon]" class="form-control">
                                            <option value="">-- Không chọn --</option>
                                            
                                            <optgroup label="🎵 Nghệ thuật - Thể thao">
                                                <option value="AM_NHAC" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'AM_NHAC' ? 'selected' : '' ?>>Âm nhạc</option>
                                                <option value="MY_THUAT" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'MY_THUAT' ? 'selected' : '' ?>>Mỹ thuật</option>
                                                <option value="THE_DUC" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'THE_DUC' ? 'selected' : '' ?>>Thể dục</option>
                                            </optgroup>
                                            
                                            <optgroup label="💻 Công nghệ">
                                                <option value="TIN_HOC" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'TIN_HOC' ? 'selected' : '' ?>>Tin học</option>
                                            </optgroup>
                                            
                                            <optgroup label="🌍 Ngoại ngữ">
                                                <option value="PHAP" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'PHAP' ? 'selected' : '' ?>>Tiếng Pháp</option>
                                                <option value="NHAT" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'NHAT' ? 'selected' : '' ?>>Tiếng Nhật</option>
                                                <option value="HAN" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'HAN' ? 'selected' : '' ?>>Tiếng Hàn</option>
                                                <option value="TRUNG" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'TRUNG' ? 'selected' : '' ?>>Tiếng Trung</option>
                                                <option value="DUC" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'DUC' ? 'selected' : '' ?>>Tiếng Đức</option>
                                                <option value="NGA" <?= ($_POST['nguyenVong'][$i]['monTuChon'] ?? '') == 'NGA' ? 'selected' : '' ?>>Tiếng Nga</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted">* Vui lòng chọn ít nhất một nguyện vọng hợp lệ (trường + môn chuyên)</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">✅ Xác nhận đăng ký</button>
                        <button type="button" class="btn btn-warning" onclick="confirmCancel()">❌ Hủy</button>
                    </div>
                </form>

            <?php else: ?>
                <!-- HIỂN THỊ THÔNG BÁO KHI KỲ ĐÃ ĐÓNG -->
                <div class="alert alert-danger text-center">
                    <h4>🔒 Kỳ tuyển sinh đã đóng!</h4>
                    <p>Kỳ tuyển sinh <strong>"<?= htmlspecialchars($selectedKyTS['tenKyTS']) ?>"</strong> đã đóng vào ngày <?= date('d/m/Y', strtotime($selectedKyTS['ngayKetThuc'])) ?>.</p>
                    <p>Không thể đăng ký cho kỳ tuyển sinh này.</p>
                    <div class="mt-3">
                        <p>Vui lòng:</p>
                        <ul style="text-align: left; display: inline-block;">
                            <li>Chọn kỳ tuyển sinh khác đang mở</li>
                            <li>Liên hệ quản trị viên để biết thêm thông tin</li>
                            <li>Đợi kỳ tuyển sinh mới</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4>📭 Không có kỳ tuyển sinh nào!</h4>
            <p>Hiện không có kỳ tuyển sinh nào trong hệ thống.</p>
            <p>Vui lòng liên hệ quản trị viên để biết thêm thông tin.</p>
            <a href="index.php?controller=parent&action=dashboard" class="btn btn-primary">← Quay về trang chủ</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmCancel() {
        if (confirm('Bạn có chắc muốn hủy đăng ký? Thông tin đã nhập sẽ bị mất.')) {
            window.location.href = 'index.php?controller=parent&action=dashboard';
        }
    }

    // VALIDATION ĐƠN GIẢN - KHÔNG LOG
    if (document.getElementById('admissionForm')) {
        document.getElementById('admissionForm').addEventListener('submit', function(e) {
            let hasValid = false;
            
            // Kiểm tra 3 nguyện vọng
            for(let i = 0; i < 3; i++) {
                const truong = document.querySelector(`select[name="nguyenVong[${i}][truong]"]`);
                const monChuyen = document.querySelector(`select[name="nguyenVong[${i}][monChuyen]"]`);
                
                if (truong && truong.value && monChuyen && monChuyen.value) {
                    hasValid = true;
                    break;
                }
            }
            
            if (!hasValid) {
                alert('Vui lòng chọn ít nhất một nguyện vọng hợp lệ (trường + môn chuyên)!');
                e.preventDefault();
            }
        });
    }
</script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>