<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">👨‍🏫 Phân công giám thị</div>
    </div>

    <!-- HIỂN THỊ THÔNG BÁO -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info" id="messageAlert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($expiredSessionsCount) && $expiredSessionsCount > 0): ?>
        <div class="alert alert-warning">
            <strong>⚠️ Lưu ý:</strong> Có <?= htmlspecialchars($expiredSessionsCount) ?> ca thi đã qua hạn đã được ẩn.
            Hệ thống chỉ hiển thị các ca thi chưa diễn ra (từ ngày <?= date('d/m/Y') ?> trở đi).
        </div>
    <?php endif; ?>

    <!-- Thông tin hiện tại -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <strong>Thông tin hiện tại</strong>
        </div>
        <div class="card-body">
            <div class="info-box" style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div class="info-item" style="background: #e8f4fd; padding: 10px 15px; border-radius: 5px; border-left: 4px solid #3498db;">
                    <span style="color: #666;">🎓 Năm học:</span>
                    <strong style="color: #2c3e50; margin-left: 5px;"><?= htmlspecialchars($selectedYear) ?></strong>
                </div>
                <div class="info-item" style="background: #e8f4fd; padding: 10px 15px; border-radius: 5px; border-left: 4px solid #3498db;">
                    <span style="color: #666;">📚 Học kỳ:</span>
                    <strong style="color: #2c3e50; margin-left: 5px;"><?= htmlspecialchars($selectedSemester) ?></strong>
                </div>
                <?php if ($selectedExam && $examInfo): ?>
                    <div class="info-item" style="background: #e8f4fd; padding: 10px 15px; border-radius: 5px; border-left: 4px solid #2ecc71;">
                        <span style="color: #666;">📋 Kỳ thi:</span>
                        <strong style="color: #2c3e50; margin-left: 5px;"><?= htmlspecialchars($examInfo['tenKyThi']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($selectedExam): ?>
        <?php if (!empty($examSubjects)): ?>
            <!-- Form chọn ca thi -->
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <strong>Chọn ca thi để phân công</strong>
                    <small style="float: right; color: #6c757d;">
                        📅 Hôm nay: <?= date('d/m/Y') ?>
                    </small>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Ca thi trong kỳ thi (chưa diễn ra)</label>
                            <select name="caThi" id="caThi" class="form-control" onchange="loadRoomsBySession()">
                                <option value="">-- Chọn ca thi --</option>
                                <?php foreach($examSubjects as $subject): ?>
                                    <option value="<?= htmlspecialchars($subject['session_key']) ?>"
                                            data-ngaythi="<?= htmlspecialchars($subject['ngayThi']) ?>"
                                            data-cathi="<?= htmlspecialchars($subject['caThi']) ?>"
                                            data-sophong="<?= htmlspecialchars($subject['so_phong'] ?? 0) ?>">
                                        📅 <strong>Ngày:</strong> <?= htmlspecialchars($subject['ngayThi']) ?> 
                                        | 🕒 <strong>Ca:</strong> <?= htmlspecialchars($subject['caThi']) ?>
                                        | 🏫 <strong>Số phòng:</strong> <?= htmlspecialchars($subject['so_phong'] ?? 0) ?>
                                        <?php if (!empty($subject['mon_hoc_display']) && $subject['mon_hoc_display'] != 'Các môn thi'): ?>
                                            <br>
                                            📚 <strong>Môn thi:</strong> <?= htmlspecialchars($subject['mon_hoc_display']) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted" id="selectedSessionInfo" style="display: none; margin-top: 5px; font-weight: bold;">
                                <!-- Hiển thị ca thi được chọn -->
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form phân công giám thị -->
            <div class="card" style="margin-bottom:20px; display: none;" id="assignmentForm">
                <div class="card-header">
                    <strong>Phân công giám thị</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?controller=supervisorAssignment&action=assignMultipleSupervisors" id="supervisorForm">
                        <input type="hidden" name="selectedNgayThi" id="selectedNgayThi" value="">
                        <input type="hidden" name="selectedBuoiThi" id="selectedBuoiThi" value="">
                        <input type="hidden" name="maKyThi" id="selectedKyThi" value="">
                        
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Phòng thi</label>
                                <select name="maPhong" id="maPhong" class="form-control" required onchange="loadAvailableSupervisors()">
                                    <option value="">-- Chọn phòng thi --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top: 15px;">
                            <div class="form-col">
                                <label class="form-label">Giám thị 1</label>
                                <select name="maGV1" id="maGV1" class="form-control" onchange="validateSupervisors()">
                                    <option value="">-- Chọn giám thị 1 --</option>
                                </select>
                            </div>
                            <div class="form-col">
                                <label class="form-label">Giám thị 2</label>
                                <select name="maGV2" id="maGV2" class="form-control" onchange="validateSupervisors()">
                                    <option value="">-- Chọn giám thị 2 --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top:15px;">
                            <div class="form-col">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    💾 Phân công giám thị
                                </button>
                                <small class="text-muted" style="display: block; margin-top: 5px;">
                                    ℹ️ Mỗi phòng thi cần 2 giám thị (có thể chọn 1 hoặc 2)
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <strong>⚠️ Không có ca thi nào chưa diễn ra</strong><br>
                Tất cả các ca thi của kỳ thi này đã diễn ra trước ngày <?= date('d/m/Y') ?>.
                Vui lòng chọn kỳ thi khác hoặc tạo kỳ thi mới.
            </div>
        <?php endif; ?>

        <!-- Danh sách phân công hiện tại -->
        <div class="card" id="assignmentsList">
            <div class="card-header">
                <h3 style="margin:0;font-size:18px; font-weight: bold; color: #2c3e50;">📋 Danh sách phân công giám thị (chưa diễn ra)</h3>
                <small style="color: #6c757d; font-size: 14px;">
                    <?= htmlspecialchars($examInfo['tenKyThi'] ?? '') ?> - Học kỳ <?= htmlspecialchars($selectedSemester) ?>, Năm học <?= htmlspecialchars($selectedYear) ?>
                </small>
            </div>
            <div class="card-body">
                <?php if (!empty($examRooms)): ?>
                    <?php 
                    $roomsBySession = [];
                    foreach ($examRooms as $room) {
                        $sessionKey = $room['ngayThi'] . '_' . $room['caThi'];
                        if (!isset($roomsBySession[$sessionKey])) {
                            $roomsBySession[$sessionKey] = [
                                'ngayThi' => $room['ngayThi'],
                                'caThi' => $room['caThi'],
                                'thoiGianBatDau' => $room['thoiGianBatDau'] ?? '07:30:00',
                                'thoiGianKetThuc' => $room['thoiGianKetThuc'] ?? '09:30:00',
                                'rooms' => []
                            ];
                        }
                        $roomsBySession[$sessionKey]['rooms'][] = $room;
                    }
                    
                    ksort($roomsBySession);
                    ?>
                    
                    <?php foreach($roomsBySession as $sessionKey => $session): ?>
                        <div class="session-section" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef;">
                            <h4 style="margin: 0 0 20px 0; padding: 10px 15px; background: #f8f9fa; border-radius: 5px; color: #2c3e50;">
                                📅 <strong>Ngày:</strong> <?= htmlspecialchars($session['ngayThi']) ?> 
                                | 🕒 <strong>Ca:</strong> <?= htmlspecialchars($session['caThi']) ?>
                            </h4>
                            
                            <?php foreach($session['rooms'] as $room): ?>
                                <div class="room-assignment" style="margin-bottom: 20px; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; background: #fff;">
                                    <h5 style="margin: 0 0 15px 0; font-size: 16px; color: #2c3e50; font-weight: bold;">
                                        🏫 <strong>Phòng:</strong> <span style="color: #3498db;"><?= htmlspecialchars($room['tenPhong']) ?></span>
                                        | 📚 <strong>Môn thi:</strong> <span style="color: #e74c3c;">
                                            <?php 
                                            $monThi = '';
                                            switch($room['maMon']) {
                                                case 'TOAN': $monThi = 'Toán'; break;
                                                case 'VAN': $monThi = 'Ngữ Văn'; break;
                                                case 'ANH': $monThi = 'Tiếng Anh'; break;
                                                case 'LY': $monThi = 'Vật Lý'; break;
                                                case 'HOA': $monThi = 'Hóa Học'; break;
                                                case 'SINH': $monThi = 'Sinh Học'; break;
                                                case 'SU': $monThi = 'Lịch Sử'; break;
                                                case 'DIA': $monThi = 'Địa Lý'; break;
                                                case 'GDCD': $monThi = 'GDCD'; break;
                                                default: $monThi = $room['maMon'];
                                            }
                                            echo htmlspecialchars($monThi);
                                            ?>
                                        </span>
                                        | 👥 <strong>Số thí sinh:</strong> <span style="color: #27ae60;"><?= htmlspecialchars($room['soLuongHienTai'] ?? 0) ?>/<?= htmlspecialchars($room['soLuongToiDa'] ?? 30) ?></span>
                                        | 🆔 <strong>Mã kỳ thi:</strong> <span style="color: #9b59b6;"><?= htmlspecialchars($room['maKyThi']) ?></span>
                                    </h5>
                                    
                                    <?php 
                                    $assignments = $roomAssignments[$room['maPhong']] ?? [];
                                    ?>
                                    
                                    <?php if (!empty($assignments)): ?>
                                        <table class="data-table" style="width:100%; font-size: 14px; border-collapse: collapse; background: white; border: 1px solid #dee2e6;">
                                            <thead>
                                                <tr style="background: #f8f9fa;">
                                                    <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6; color: #2c3e50; font-weight: 600;">STT</th>
                                                    <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6; color: #2c3e50; font-weight: 600;">Giáo viên</th>
                                                    <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6; color: #2c3e50; font-weight: 600;">Môn giảng dạy</th>
                                                    <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; color: #2c3e50; font-weight: 600;">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($assignments as $index => $assignment): ?>
                                                    <tr style="background: #ffffff;">
                                                        <td style="padding: 12px; border: 1px solid #dee2e6; color: #2c3e50;"><?= $index + 1 ?></td>
                                                        <td style="padding: 12px; border: 1px solid #dee2e6; color: #2c3e50;">
                                                            <strong><?= htmlspecialchars($assignment['hoVaTen']) ?></strong>
                                                        </td>
                                                        <td style="padding: 12px; border: 1px solid #dee2e6; color: #2c3e50;">
                                                            <?= htmlspecialchars($assignment['monGiangDayText'] ?? $assignment['monGiangDay']) ?>
                                                        </td>
                                                        <td style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">
                                                            <form method="POST" action="index.php?controller=supervisorAssignment&action=cancelSupervisorAssignment" style="display:inline;">
                                                                <input type="hidden" name="maPCGT" value="<?= $assignment['maPCGT'] ?>">
                                                                <input type="hidden" name="maKyThi" value="<?= htmlspecialchars($room['maKyThi']) ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                                        style="padding: 6px 12px; font-size: 12px; background: #dc3545; border: 1px solid #dc3545; color: white; border-radius: 4px; cursor: pointer;"
                                                                        onclick="return confirm('Bạn có chắc muốn hủy phân công giám thị <?= htmlspecialchars($assignment['hoVaTen']) ?>?')">
                                                                    🗑️ Hủy
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div style="text-align: center; padding: 20px; color: #6c757d;">
                                            <div style="font-size: 48px; margin-bottom: 10px;">📭</div>
                                            <p style="font-size: 16px; margin: 0; color: #6c757d;">Chưa có phân công giám thị cho phòng này</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #6c757d;">
                        <div style="font-size: 48px; margin-bottom: 15px;">🏫</div>
                        <p style="font-size: 16px; margin: 0; color: #6c757d;">Không có phòng thi nào chưa diễn ra cho kỳ thi này</p>
                        <p style="font-size: 14px; margin: 10px 0 0 0; color: #6c757d;">Tất cả các phòng thi đều đã diễn ra trước ngày <?= date('d/m/Y') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <strong>⚠️ Không có kỳ thi "Giữa kỳ" hoặc "Cuối kỳ" nào đang mở</strong><br>
            Hiện tại không có kỳ thi nào trong học kỳ <?= htmlspecialchars($selectedSemester) ?>, năm học <?= htmlspecialchars($selectedYear) ?>.
            Vui lòng tạo kỳ thi "Giữa kỳ" hoặc "Cuối kỳ" trước khi phân công giám thị.
        </div>
    <?php endif; ?>
</div>

<script>


function loadRoomsBySession() {
    const sessionSelect = document.getElementById('caThi');
    const selectedOption = sessionSelect.options[sessionSelect.selectedIndex];
    const sessionKey = sessionSelect.value;
    
    if (!sessionKey) {
        document.getElementById('assignmentForm').style.display = 'none';
        resetForm();
        return;
    }
    
    const ngayThi = selectedOption.getAttribute('data-ngaythi');
    const caThi = selectedOption.getAttribute('data-cathi');
    const soPhong = selectedOption.getAttribute('data-sophong');
    
    document.getElementById('assignmentForm').style.display = 'block';
    document.getElementById('selectedNgayThi').value = ngayThi;
    document.getElementById('selectedBuoiThi').value = caThi;
    
    const sessionInfo = document.getElementById('selectedSessionInfo');
    sessionInfo.innerHTML = `📅 <strong>Ngày:</strong> ${ngayThi} | 🕒 <strong>Ca:</strong> ${caThi} | 🏫 <strong>Số phòng:</strong> ${soPhong}`;
    sessionInfo.style.display = 'block';
    
    resetForm();
    
    // Gọi API chỉ với ngày và ca thi
    fetch('index.php?controller=supervisorAssignment&action=getExamRoomsBySession', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'ngayThi=' + encodeURIComponent(ngayThi) +
              '&buoiThi=' + encodeURIComponent(caThi)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(rooms => {
        const roomSelect = document.getElementById('maPhong');
        roomSelect.innerHTML = '<option value="">-- Chọn phòng thi --</option>';
        roomSelect.disabled = false;
        
        if (rooms && rooms.length > 0 && !rooms.error) {
            rooms.forEach(room => {
                let tenMon = '';
                switch(room.maMon) {
                    case 'TOAN': tenMon = 'Toán'; break;
                    case 'VAN': tenMon = 'Ngữ Văn'; break;
                    case 'ANH': tenMon = 'Tiếng Anh'; break;
                    case 'LY': tenMon = 'Vật Lý'; break;
                    case 'HOA': tenMon = 'Hóa Học'; break;
                    case 'SINH': tenMon = 'Sinh Học'; break;
                    case 'SU': tenMon = 'Lịch Sử'; break;
                    case 'DIA': tenMon = 'Địa Lý'; break;
                    case 'GDCD': tenMon = 'GDCD'; break;
                    default: tenMon = room.maMon;
                }
                
                const option = document.createElement('option');
                option.value = room.maPhong;
                option.textContent = `🏫 ${room.tenPhong} | 📚 ${tenMon} | 👥 ${room.soLuongHienTai || 0}/${room.soLuongToiDa || 30} thí sinh`;
                option.setAttribute('data-maPhong', room.maPhong);
                option.setAttribute('data-maMon', room.maMon);
                option.setAttribute('data-maKyThi', room.maKyThi);
                roomSelect.appendChild(option);
            });
        } else {
            let errorMessage = '-- Không có phòng thi cho ca thi này --';
            if (rooms && rooms.error) {
                errorMessage = `-- ${rooms.error} --`;
            }
            roomSelect.innerHTML = `<option value="">${errorMessage}</option>`;
            roomSelect.disabled = true;
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        document.getElementById('maPhong').innerHTML = '<option value="">-- Lỗi tải phòng thi --</option>';
        document.getElementById('maPhong').disabled = true;
    });
}

function loadAvailableSupervisors() {
    const maPhong = document.getElementById('maPhong').value;
    const roomOption = document.getElementById('maPhong').options[document.getElementById('maPhong').selectedIndex];
    const maKyThi = roomOption.getAttribute('data-maKyThi');
    const sessionSelect = document.getElementById('caThi');
    const sessionOption = sessionSelect.options[sessionSelect.selectedIndex];
    const ngayThi = sessionOption.getAttribute('data-ngaythi');
    const caThi = sessionOption.getAttribute('data-cathi');
    
    if (!maPhong || !maKyThi || !ngayThi || !caThi) {
        resetSupervisorDropdowns();
        return;
    }
    
    // Cập nhật hidden field maKyThi trong form
    document.getElementById('selectedKyThi').value = maKyThi;
    
    resetSupervisorDropdowns();
    
    const gvSelect1 = document.getElementById('maGV1');
    const gvSelect2 = document.getElementById('maGV2');
    gvSelect1.innerHTML = '<option value="">-- Đang tải... --</option>';
    gvSelect1.disabled = true;
    gvSelect2.innerHTML = '<option value="">-- Đang tải... --</option>';
    gvSelect2.disabled = true;
    
    // Gọi API lấy thông tin giáo viên
    fetch('index.php?controller=supervisorAssignment&action=getAvailableSupervisors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'maPhong=' + encodeURIComponent(maPhong) + 
              '&maKyThi=' + encodeURIComponent(maKyThi) +
              '&ngayThi=' + encodeURIComponent(ngayThi) +
              '&caThi=' + encodeURIComponent(caThi)
    })
    .then(response => response.json())
    .then(teachers => {
        const gvSelect1 = document.getElementById('maGV1');
        const gvSelect2 = document.getElementById('maGV2');
        gvSelect1.innerHTML = '<option value="">-- Chọn giám thị 1 --</option>';
        gvSelect2.innerHTML = '<option value="">-- Chọn giám thị 2 --</option>';
        gvSelect1.disabled = false;
        gvSelect2.disabled = false;
        
        if (teachers && teachers.length > 0 && !teachers.error) {
            teachers.forEach(teacher => {
                const option1 = document.createElement('option');
                option1.value = teacher.maGV;
                option1.textContent = `${teacher.hoVaTen} - ${teacher.monGiangDayText || teacher.monGiangDay}`;
                gvSelect1.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = teacher.maGV;
                option2.textContent = `${teacher.hoVaTen} - ${teacher.monGiangDayText || teacher.monGiangDay}`;
                gvSelect2.appendChild(option2);
            });
        } else {
            let errorMsg = '-- Không có giáo viên khả dụng --';
            if (teachers && teachers.error) {
                errorMsg = `-- ${teachers.error} --`;
            }
            gvSelect1.innerHTML = `<option value="">${errorMsg}</option>`;
            gvSelect2.innerHTML = `<option value="">${errorMsg}</option>`;
            gvSelect1.disabled = true;
            gvSelect2.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Lỗi tải danh sách giáo viên');
    });
}

function resetForm() {
    const gvSelect1 = document.getElementById('maGV1');
    const gvSelect2 = document.getElementById('maGV2');
    const submitBtn = document.getElementById('submitBtn');
    const roomSelect = document.getElementById('maPhong');
    const sessionInfo = document.getElementById('selectedSessionInfo');
    
    if (roomSelect) {
        roomSelect.innerHTML = '<option value="">-- Chọn ca thi trước --</option>';
        roomSelect.disabled = true;
    }
    
    if (gvSelect1) {
        gvSelect1.innerHTML = '<option value="">-- Chọn phòng thi trước --</option>';
        gvSelect1.disabled = true;
    }
    
    if (gvSelect2) {
        gvSelect2.innerHTML = '<option value="">-- Chọn phòng thi trước --</option>';
        gvSelect2.disabled = true;
    }
    
    if (submitBtn) submitBtn.disabled = true;
    if (sessionInfo) {
        sessionInfo.style.display = 'none';
        sessionInfo.innerHTML = '';
    }
}

function resetSupervisorDropdowns() {
    const gvSelect1 = document.getElementById('maGV1');
    const gvSelect2 = document.getElementById('maGV2');
    const submitBtn = document.getElementById('submitBtn');
    
    if (gvSelect1) {
        gvSelect1.innerHTML = '<option value="">-- Chọn phòng thi trước --</option>';
        gvSelect1.disabled = true;
    }
    
    if (gvSelect2) {
        gvSelect2.innerHTML = '<option value="">-- Chọn phòng thi trước --</option>';
        gvSelect2.disabled = true;
    }
    
    if (submitBtn) submitBtn.disabled = true;
}

function validateSupervisors() {
    const gvSelect1 = document.getElementById('maGV1');
    const gvSelect2 = document.getElementById('maGV2');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!gvSelect1 || !gvSelect2 || !submitBtn) return;
    
    const maGV1 = gvSelect1.value;
    const maGV2 = gvSelect2.value;
    
    if (maGV1 && maGV2 && maGV1 === maGV2) {
        alert('Không được chọn cùng một giáo viên cho 2 vị trí giám thị!');
        gvSelect2.value = '';
        submitBtn.disabled = true;
        return;
    }
    
    if (!maGV1 && !maGV2) {
        submitBtn.disabled = true;
    } else {
        submitBtn.disabled = false;
    }
}

function showError(message) {
    const gvSelect1 = document.getElementById('maGV1');
    const gvSelect2 = document.getElementById('maGV2');
    const submitBtn = document.getElementById('submitBtn');
    
    if (gvSelect1) {
        gvSelect1.innerHTML = `<option value="">-- ${message} --</option>`;
        gvSelect1.disabled = true;
    }
    
    if (gvSelect2) {
        gvSelect2.innerHTML = `<option value="">-- ${message} --</option>`;
        gvSelect2.disabled = true;
    }
    
    if (submitBtn) submitBtn.disabled = true;
}

// Cải thiện quá trình submit form
const supervisorForm = document.getElementById('supervisorForm');
if (supervisorForm) {
    supervisorForm.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Đang phân công...';
        }
        
        return true;
    });
}
</script>

<?php include "views/layout/footer.php"; ?>