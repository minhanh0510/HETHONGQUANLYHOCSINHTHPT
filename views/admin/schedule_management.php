<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<style>
/* STYLE CHO QUẢN LÝ LỊCH HỌC */
.filter-section {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.schedule-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.subjects-panel {
    width: 300px;
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 15px;
    height: fit-content;
}

.subject-item {
    background-color: white;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 4px;
    cursor: move;
    border: 1px solid #e1e5eb;
    border-left: 4px solid #3498db;
    transition: all 0.2s;
    font-weight: 600;
}

.subject-item:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.subject-item.text-muted {
    opacity: 0.6;
    cursor: not-allowed;
}

.subject-item small {
    font-weight: 400;
    color: #666;
    display: block;
    margin-top: 4px;
}

.schedule-panel {
    flex: 1;
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.schedule-grid {
    display: grid;
    grid-template-columns: 80px repeat(6, 1fr);
    gap: 2px;
    background-color: #e1e5eb;
    border: 1px solid #e1e5eb;
    margin-top: 15px;
}

.schedule-header {
    background-color: #3498db;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    font-size: 14px;
}

.schedule-time {
    background-color: #f8f9fa;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.schedule-cell {
    background-color: white;
    padding: 10px;
    min-height: 90px;
    cursor: pointer;
    transition: background-color 0.2s;
    position: relative;
    font-size: 13px;
}

.schedule-cell:hover {
    background-color: #f0f8ff;
}

.schedule-cell.highlight {
    background-color: #e8f4fd !important;
    border: 2px dashed #3498db !important;
}

.schedule-cell.week-locked-cell {
    cursor: not-allowed;
    opacity: 0.7;
    background-color: #f8f9fa !important;
}

/* Ô gợi ý */
.schedule-cell.suggested-slot {
    background: #e8f5e8 !important;
    border: 2px dashed #28a745 !important;
}

/* Ô cảnh báo */
.schedule-cell.conflict-slot {
    background: #ffe6e6 !important;
    border: 2px dashed #dc3545 !important;
}

.tkb-entry {
    background: #3498db;
    color: white;
    padding: 6px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.tkb-entry strong {
    display: block;
    margin-bottom: 2px;
}

.tkb-entry small {
    display: block;
    font-size: 11px;
    opacity: 0.9;
    margin-top: 2px;
}

.week-locked {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    color: #856404;
    display: flex;
    align-items: center;
    gap: 10px;
}

.week-locked strong {
    font-weight: 600;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-box {
    width: 450px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    padding: 25px;
}

.modal-header {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #2c3e50;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close-btn {
    cursor: pointer;
    font-size: 24px;
    color: #7f8c8d;
}

.close-btn:hover {
    color: #34495e;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 15px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

.badge-warning {
    background-color: #f39c12;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.text-danger {
    color: #e74c3c !important;
}

.text-muted {
    color: #7f8c8d !important;
}

.schedule-status {
    background-color: #e8f4fd;
    border: 1px solid #b8daff;
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.schedule-status.info {
    background-color: #e8f4fd;
    border-color: #b8daff;
}

.schedule-status.warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
}

.template-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.d-block {
    display: block;
}

.mt-1 {
    margin-top: 4px;
}

.schedule-cell.suggested-slot {
    background: #e8f5e8 !important;
    border: 2px dashed #28a745 !important;
    position: relative;
}

.schedule-cell.suggested-slot::after {
    content: "✓";
    position: absolute;
    top: 2px;
    right: 2px;
    color: #28a745;
    font-weight: bold;
    font-size: 12px;
}

/* Tooltip style */
.schedule-cell[title] {
    position: relative;
}

.schedule-cell[title]:hover::before {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
}
</style>

<div class="main-content">
    <!-- HEADER -->
    <div class="content-header">
        <div class="page-title">📅 Quản lý thời khóa biểu</div>
        <div class="action-buttons">
            <button class="btn btn-primary" onclick="exportSchedule()">Xuất báo cáo</button>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <div class="filter-group">
            <label class="form-label">Khối</label>
            <select name="khoi" class="form-control" onchange="updateFilter()">
                <option value="K10" <?=($khoi=="K10")?"selected":""?>>Khối 10</option>
                <option value="K11" <?=($khoi=="K11")?"selected":""?>>Khối 11</option>
                <option value="K12" <?=($khoi=="K12")?"selected":""?>>Khối 12</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="form-label">Lớp</label>
            <select name="lop" class="form-control" onchange="updateFilter()">
                <?php foreach($lopList as $l): ?>
                    <option value="<?=$l['maLop']?>" <?=($maLop==$l['maLop'])?"selected":""?>>
                        <?=$l['tenLop']?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label class="form-label">Tuần</label>
            <input type="week" name="tuan" class="form-control" value="<?=$week?>" onchange="updateFilter()">
        </div>

        <div class="filter-group">
            <label class="form-label">Học kỳ</label>
            <select name="hocKy" class="form-control" onchange="updateFilter()">
                <option value="1" <?=($hocKy==1)?"selected":""?>>HK1</option>
                <option value="2" <?=($hocKy==2)?"selected":""?>>HK2</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="form-label">Năm học</label>
            <input type="text" name="namHoc" class="form-control" value="<?=$namHoc?>" onchange="updateFilter()">
        </div>
    </div>

    <!-- STATUS & ACTIONS -->
    <?php 
    $isWeekLocked = $model->isWeekLocked($tbd);
    if($isWeekLocked): ?>
        <div class="week-locked">
            <strong>⚠️ Tuần đã khóa</strong>
            <span>Tuần này đã được khóa, không thể thay đổi lịch học.</span>
        </div>
    <?php endif; ?>

    <div class="schedule-status <?= $hasCustomSchedule ? 'warning' : 'info' ?>">
        <div>
            <strong>Trạng thái: </strong>
            <?php if($hasTemplate): ?>
                <?php if($hasCustomSchedule): ?>
                    <span class="badge badge-warning">Đang xem lịch tuần chỉnh sửa</span>
                    <small class="d-block mt-1">Tuần này đã được chỉnh sửa riêng so với lịch mẫu</small>
                <?php else: ?>
                    <span class="badge badge-success">Đang xem lịch mẫu</span>
                    <small class="d-block mt-1">Lịch này được áp dụng cho cả học kỳ</small>
                <?php endif; ?>
            <?php else: ?>
                <span class="badge badge-info">Chưa có lịch mẫu</span>
                <small class="d-block mt-1">Hãy tạo lịch mẫu cho học kỳ này</small>
            <?php endif; ?>
        </div>
        
        <div class="template-actions">
            <?php if($hasTemplate && !$isWeekLocked): ?>
                <?php if($hasCustomSchedule): ?>
                    <a class="btn btn-secondary btn-sm" 
                       href="index.php?controller=scheduleAdmin&action=resetWeek&lop=<?=$maLop?>&tuan=<?=$week?>&hocKy=<?=$hocKy?>&namHoc=<?=$namHoc?>"
                       onclick="return confirm('Xóa lịch tuần này và quay về dùng lịch mẫu?')">
                       🔄 Dùng lịch mẫu
                    </a>
                <?php else: ?>
                    <a class="btn btn-warning btn-sm" 
                       href="index.php?controller=scheduleAdmin&action=applyTemplate&lop=<?=$maLop?>&tuan=<?=$week?>&hocKy=<?=$hocKy?>&namHoc=<?=$namHoc?>"
                       onclick="return confirm('Tạo bản sao lịch mẫu để chỉnh sửa riêng cho tuần này?')">
                       ✏️ Chỉnh sửa tuần này
                    </a>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if(!$isWeekLocked): ?>
                <a class="btn btn-success btn-sm"
                   href="index.php?controller=scheduleAdmin&action=autoArrange&lop=<?=$maLop?>&hocKy=<?=$hocKy?>&namHoc=<?=$namHoc?>"
                   onclick="return confirm('Tạo lịch mẫu tự động cho cả học kỳ? Lịch cũ sẽ bị xóa.')">
                   ⚙️ Tạo lịch mẫu
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- MESSAGES -->
    <?php if(!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?=$_SESSION['message']; unset($_SESSION['message']);?></div>
    <?php endif; ?>

    <?php if(!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?=$_SESSION['error']; unset($_SESSION['error']);?></div>
    <?php endif; ?>

    <!-- MAIN CONTENT -->
    <div class="schedule-container">
        <!-- PANEL MÔN HỌC -->
        <div class="subjects-panel">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">📚 Môn học</h3>
            <?php foreach($monList as $m): ?>
                <?php 
                $hourCheck = $model->checkWeeklyHours($maLop, $m['maMon'], $tbd, $tkt);
                $isFull = $hourCheck['vuot'];
                ?>
                <div class="subject-item <?= $isFull ? 'text-muted' : '' ?>" 
                     draggable="<?= $isFull || $isWeekLocked ? 'false' : 'true' ?>"
                     data-mamon="<?=$m['maMon']?>"
                     data-tiet="<?=$m['soTiet']?>"
                     onclick="showSuggestionsForSubject('<?=$m['maMon']?>')">
                     <?=$m['tenMon']?> 
                     <small><?=$m["soTiet"]?> tiết/tuần - Đã xếp: <?=$hourCheck['daXep']?></small>
                     <?php if($isFull): ?>
                        <small class="text-danger">Đã đủ số tiết</small>
                     <?php endif; ?>
                     <?php if($isWeekLocked): ?>
                        <small class="text-danger">Tuần đã khóa</small>
                     <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- LỊCH HỌC -->
        <div class="schedule-panel">
            <h3 style="color: #2c3e50; margin-bottom: 5px;">
                Lịch học lớp <?=$maLop?>
                <?php if($hasCustomSchedule): ?>
                    <span class="badge badge-warning" style="margin-left: 10px;">Tuần chỉnh sửa</span>
                <?php endif; ?>
                <?php if($isWeekLocked): ?>
                    <span class="badge badge-danger" style="margin-left: 10px;">Tuần đã khóa</span>
                <?php endif; ?>
            </h3>
            <p style="color: #7f8c8d; margin-bottom: 15px;">
                <?=$tbd?> → <?=$tkt?> 
                | HK<?=$hocKy?> - Năm học: <?=$namHoc?>
            </p>

            <div class="schedule-grid" id="scheduleGrid">
                <!-- HEADER -->
                <div class="schedule-header">Tiết</div>
                <div class="schedule-header">Thứ 2</div>
                <div class="schedule-header">Thứ 3</div>
                <div class="schedule-header">Thứ 4</div>
                <div class="schedule-header">Thứ 5</div>
                <div class="schedule-header">Thứ 6</div>
                <div class="schedule-header">Thứ 7</div>

                <!-- NỘI DUNG LỊCH -->
                <?php
                $grid = [];
                foreach($tkb as $row){
                    $grid[$row["tiet"]][$row["thu"]] = $row;
                }

                for($tiet=1; $tiet<=5; $tiet++):
                    echo "<div class='schedule-time'>Tiết $tiet</div>";
                    
                    for($thu=2; $thu<=7; $thu++):
                        $cell = $grid[$tiet][$thu] ?? null;
                        $cellClass = $isWeekLocked ? 'week-locked-cell' : '';

                        echo "<div class='schedule-cell $cellClass' 
                                  data-tiet='$tiet' 
                                  data-thu='$thu'
                                  data-lop='$maLop'
                                  data-tbd='$tbd'
                                  data-tkt='$tkt'
                                  data-default-room='$defaultRoom'";
                        
                        if($cell){
                            echo " data-current='".htmlspecialchars(json_encode($cell), ENT_QUOTES, 'UTF-8')."'";
                        }
                        
                        echo ">";

                        if($cell){
                            echo "<div class='tkb-entry'>
                                    <strong>".$cell['tenMon']."</strong>
                                    <small>GV: ".$cell['tenGV']."</small>
                                    <small>Phòng: ".($cell['tenPhong'] ?? 'Chưa xếp')."</small>
                                  </div>";
                                  
                            if(!$isWeekLocked){
                                echo "<div style='position:absolute; top:5px; right:5px;'>
                                        <a href='index.php?controller=scheduleAdmin&action=delete&id=".$cell['maTKB']."' 
                                           class='btn btn-sm btn-danger'
                                           style='padding: 2px 6px; font-size: 12px;'
                                           onclick='return confirm(\"Xóa tiết học này?\")'>×</a>
                                      </div>";
                            }
                        }

                        echo "</div>";
                    endfor;
                endfor;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL THÊM/CẬP NHẬT LỊCH -->
<div class="modal" id="tkbModal">
    <div class="modal-box">
        <div class="modal-header">
            <span id="modalTitle">Thêm lịch học</span>
            <span class="close-btn" onclick="hideModal()">&times;</span>
        </div>

        <form method="POST" action="index.php?controller=scheduleAdmin&action=save" id="scheduleForm">
            <input type="hidden" name="maLop" id="fmMaLop">
            <input type="hidden" name="thu" id="fmThu">
            <input type="hidden" name="tiet" id="fmTiet">
            <input type="hidden" name="tbd" id="fmTbd">
            <input type="hidden" name="tkt" id="fmTkt">
            <input type="hidden" name="hocKy" value="<?=$hocKy?>">
            <input type="hidden" name="namHoc" value="<?=$namHoc?>">
            <input type="hidden" name="is_edit" id="fmEdit" value="0">
            <input type="hidden" name="maTKB" id="fmMaTKB">

            <div class="form-group">
                <label class="form-label">Môn học</label>
                <select name="maMon" id="fmMon" class="form-control" onchange="onSubjectChange()">
                    <option value="">-- Chọn môn --</option>
                    <?php foreach($monList as $m): ?>
                        <option value="<?=$m['maMon']?>"><?=$m['tenMon']?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Giáo viên</label>
<select name="maGV" id="fmGV" class="form-control" onchange="checkRealTimeConflict()">
    <option value="">-- Chọn giáo viên --</option>
    <?php 
    // Lọc giáo viên theo môn học (sẽ được cập nhật bằng JavaScript)
    foreach($gvList as $g): 



        // Ban đầu không hiển thị, sẽ được lọc bằng JS
    ?>
        <option value="<?=$g['maGV']?>" data-mon="<?=$g['monGiangDay']?>" style="display: none;">
            <?=$g['hoVaTen']?> - <?=$g['monGiangDay']?>
        </option>
    <?php endforeach; ?>
</select>
            </div>

            <div class="form-group">
                <label class="form-label">Phòng học</label>
                <select name="maPhong" id="fmPhong" class="form-control" onchange="checkRealTimeConflict()">
                    <option value="">-- Chọn phòng --</option>
                    <?php foreach($roomList as $r): ?>
                        <option value="<?=$r['maPhong']?>" <?= ($r['maPhong'] == $defaultRoom) ? 'selected' : '' ?>>
                            <?=$r['tenPhong']?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="conflictWarning" class="alert alert-danger" style="display: none; margin-top: 15px;"></div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Lưu lại</button>
                <button type="button" class="btn btn-secondary" onclick="hideModal()">Hủy thao tác</button>
            </div>
        </form>
    </div>
</div>

<script>
let dragMon = null;
let currentSuggestions = [];
let isWeekLocked = <?= $isWeekLocked ? 'true' : 'false' ?>;

// CẬP NHẬT FILTER
function updateFilter() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = 'index.php';
    
    const params = {
        controller: 'scheduleAdmin',
        action: 'index',
        khoi: document.querySelector('[name="khoi"]').value,
        lop: document.querySelector('[name="lop"]').value,
        tuan: document.querySelector('[name="tuan"]').value,
        hocKy: document.querySelector('[name="hocKy"]').value,
        namHoc: document.querySelector('[name="namHoc"]').value
    };
    
    for (const key in params) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = params[key];
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}

// DRAG AND DROP
document.querySelectorAll(".subject-item").forEach(item => {
    item.addEventListener("dragstart", (e) => {
        if(item.getAttribute('draggable') === 'true'){
            dragMon = item.dataset.mamon;
        } else {
            e.preventDefault();
        }
    });
});

document.querySelectorAll(".schedule-cell").forEach(cell => {
    if(cell.classList.contains('week-locked-cell')) return;

    cell.addEventListener("dragover", e => {
        e.preventDefault();
        cell.classList.add("highlight");
    });

    cell.addEventListener("dragleave", () => {
        cell.classList.remove("highlight");
    });

    cell.addEventListener("drop", (e) => {
        e.preventDefault();
        cell.classList.remove("highlight");
        if(dragMon){
            openForm(cell, dragMon);
        }
    });

    cell.addEventListener("click", () => {
        if(!isWeekLocked){
            openForm(cell, null);
        }
    });
});

// MODAL FUNCTIONS
function openForm(cell, mon) {
    if(isWeekLocked) {
        alert("Tuần này đã khóa, không thể thay đổi lịch học!");
        return;
    }

    document.getElementById("tkbModal").style.display = "flex";
    document.getElementById("fmMaLop").value = cell.dataset.lop;
    document.getElementById("fmThu").value = cell.dataset.thu;
    document.getElementById("fmTiet").value = cell.dataset.tiet;
    document.getElementById("fmTbd").value = cell.dataset.tbd;
    document.getElementById("fmTkt").value = cell.dataset.tkt;

    const existingEntry = cell.querySelector('.tkb-entry');
    
    if(existingEntry && cell.dataset.current) {
        // Chế độ cập nhật
        document.getElementById("fmEdit").value = "1";
        document.getElementById("modalTitle").textContent = "Cập nhật lịch học";
        
        try {
            const currentData = JSON.parse(cell.dataset.current);
            document.getElementById("fmMon").value = currentData.maMon || '';
            document.getElementById("fmGV").value = currentData.maGV || '';
            document.getElementById("fmPhong").value = currentData.maPhong || cell.dataset.defaultRoom || '';
            document.getElementById("fmMaTKB").value = currentData.maTKB || '';
        } catch(e) {
            console.error('Error parsing current data:', e);
        }
    } else {
        // Chế độ thêm mới
        document.getElementById("fmEdit").value = "0";
        document.getElementById("modalTitle").textContent = "Thêm lịch học";
        document.getElementById("fmMaTKB").value = "";
        
        if(mon) {
            document.getElementById("fmMon").value = mon;
        } else {
            document.getElementById("fmMon").value = "";
        }
        
        // Reset form
        document.getElementById("fmGV").value = "";
        document.getElementById("fmPhong").value = cell.dataset.defaultRoom || "";
    }
    
    // Hiển thị gợi ý và kiểm tra xung đột
    if(document.getElementById("fmMon").value) {
        onSubjectChange();
    }
    
    // Kiểm tra xung đột real-time
    setTimeout(checkRealTimeConflict, 100);
}

function hideModal() {
    if (confirm("Bạn có chắc chắn muốn hủy thao tác?")) {
        document.getElementById("tkbModal").style.display = "none";
        showSuggestions();
    }
}

// SUGGESTION FUNCTIONS
function showSuggestions() {
    document.querySelectorAll('.suggested-slot, .conflict-slot').forEach(cell => {
        cell.classList.remove('suggested-slot', 'conflict-slot');
    });
    currentSuggestions = [];
}

function showSuggestionsForSubject(maMon) {
    if(isWeekLocked) {
        alert("Tuần này đã khóa, không thể thay đổi lịch học!");
        return;
    }

    fetch(`index.php?controller=scheduleAdmin&action=getSuggestedSlots&lop=<?=$maLop?>&mon=${maMon}&tuan=<?=$week?>`)
        .then(response => response.json())
        .then(suggestions => {
            showSuggestions();
            
            suggestions.forEach(slot => {
                const cell = document.querySelector(`[data-thu="${slot.thu}"][data-tiet="${slot.tiet}"]`);
                if(cell) {
                    cell.classList.add('suggested-slot');
                    currentSuggestions.push(cell);
                }
            });
            
            if(suggestions.length === 0) {
                alert('Không có ô trống phù hợp cho môn học này!');
            }
        })
        .catch(error => {
            console.error('Error fetching suggestions:', error);
            alert('Có lỗi xảy ra khi tải gợi ý!');
        });
}

function onSubjectChange() {
    const maMon = document.getElementById('fmMon').value;
    if(maMon) {
        showSuggestionsForSubject(maMon);
    } else {
        showSuggestions();
    }
}

// REAL-TIME CONFLICT CHECKING
function checkRealTimeConflict() {
    const maLop = document.getElementById('fmMaLop').value;
    const thu = document.getElementById('fmThu').value;
    const tiet = document.getElementById('fmTiet').value;
    const maGV = document.getElementById('fmGV').value;
    const maPhong = document.getElementById('fmPhong').value;
    const tbd = document.getElementById('fmTbd').value;
    const tkt = document.getElementById('fmTkt').value;
    
    if (!maGV || !maPhong || !maLop) return;
    
    // Gọi API kiểm tra xung đột
    fetch(`index.php?controller=scheduleAdmin&action=checkConflict&lop=${maLop}&thu=${thu}&tiet=${tiet}&gv=${maGV}&phong=${maPhong}&tbd=${tbd}&tkt=${tkt}`)
        .then(response => response.json())
        .then(conflicts => {
            const warningDiv = document.getElementById('conflictWarning');
            if (conflicts.length > 0) {
                warningDiv.style.display = 'block';
                let conflictText = '<strong>⚠️ Cảnh báo xung đột:</strong><ul style="margin: 8px 0 0 0; padding-left: 20px;">';
                conflicts.forEach(conflict => {
                    conflictText += `<li>${conflict}</li>`;
                });
                conflictText += '</ul>';
                warningDiv.innerHTML = conflictText;
            } else {
                warningDiv.style.display = 'none';
                warningDiv.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error checking conflict:', error);
        });
}

// CLOSE MODAL WHEN CLICKING OUTSIDE
window.onclick = function(event) {
    const modal = document.getElementById('tkbModal');
    if (event.target == modal) {
        hideModal();
    }
}

function exportSchedule() {
    alert("Xuất báo cáo lịch học!");
    // Có thể mở rộng để xuất file Excel/PDF
}

// Thêm event listeners cho form
document.addEventListener('DOMContentLoaded', function() {
    const fmGV = document.getElementById('fmGV');
    const fmPhong = document.getElementById('fmPhong');
    
    if(fmGV) {
        fmGV.addEventListener('change', checkRealTimeConflict);
    }
    
    if(fmPhong) {
        fmPhong.addEventListener('change', checkRealTimeConflict);
    }
});


function onSubjectChange() {
    const maMon = document.getElementById('fmMon').value;
    const gvSelect = document.getElementById('fmGV');
    
    // Xóa tất cả options trừ option mặc định
    gvSelect.innerHTML = '<option value="">-- Chọn giáo viên --</option>';
    
    if(maMon && gvByMon[maMon]) {
        // Thêm các giáo viên của môn đó
        gvByMon[maMon].forEach(gv => {
            const option = document.createElement('option');
            option.value = gv.maGV;
            option.textContent = gv.hoVaTen;
            gvSelect.appendChild(option);
        });
        
        // Nếu chỉ có một giáo viên, chọn luôn
        if(gvByMon[maMon].length === 1) {
            gvSelect.value = gvByMon[maMon][0].maGV;
        }
    }
    
    if(maMon) {
        showSuggestionsForSubject(maMon);
    } else {
        showSuggestions();
    }
    
    // Kiểm tra xung đột nếu đã chọn giáo viên
    checkRealTimeConflict();
}


    const gvByMon = {
    <?php foreach($monList as $m): ?>
        '<?=$m['maMon']?>': [
            <?php 
            $gvMon = array_filter($gvList, function($g) use ($m) {
                return $g['monGiangDay'] == $m['tenMon'];
            });
            foreach($gvMon as $g): ?>
                {maGV: '<?=$g['maGV']?>', hoVaTen: '<?=$g['hoVaTen']?>'},
            <?php endforeach; ?>
        ],
    <?php endforeach; ?>
};
}
</script>

<?php include "views/layout/footer.php"; ?>