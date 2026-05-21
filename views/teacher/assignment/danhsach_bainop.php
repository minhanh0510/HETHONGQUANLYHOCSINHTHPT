<?php 
// views/teacher/assignment/danhsach_bainop.php
include 'views/layout/header.php'; 
include 'views/layout/sidebar_teacher.php'; 
?>

<main class="main-content">
    <div class="py-4">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 20px;">
            <a href="index.php?controller=assignmentTeacher&action=index" style="color: #3498db; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Bài tập
            </a>
            <span style="color: #7f8c8d;"> / </span>
            <span style="color: #7f8c8d;">Danh sách bài nộp</span>
        </nav>

        <!-- Thông tin bài tập -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
            <h3 style="margin: 0 0 15px 0;">
                <i class="fas fa-clipboard-check"></i> <?php echo htmlspecialchars($baiTap['tenBaiTap']); ?>
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <i class="fas fa-book"></i> <strong>Môn:</strong> <?php echo $baiTap['tenMon']; ?>
                </div>
                <div>
                    <i class="fas fa-users"></i> <strong>Lớp:</strong> <?php echo $baiTap['tenLop']; ?>
                </div>
                <div>
                    <i class="fas fa-calendar-alt"></i> <strong>Hạn nộp:</strong> <?php echo date('d/m/Y H:i', strtotime($baiTap['thoiHanNop'])); ?>
                </div>
                <div>
                    <span style="background: <?php echo $baiTap['trangThai'] == 'DangMo' ? '#28a745' : '#6c757d'; ?>; padding: 5px 15px; border-radius: 15px; display: inline-block;">
                        <i class="fas fa-<?php echo $baiTap['trangThai'] == 'DangMo' ? 'lock-open' : 'lock'; ?>"></i>
                        <?php echo $baiTap['trangThai'] == 'DangMo' ? 'Đang mở' : 'Đã đóng'; ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Thống kê nhanh -->
        <?php 
        $tongHS = count($hocSinhList);
        $daNop = count($baiNopList);
        $chuaNop = $tongHS - $daNop;
        $daCham = 0;
        foreach($baiNopList as $bn) {
            if($bn['trangThai'] == 'DaCham') $daCham++;
        }
        $chuaCham = $daNop - $daCham;
        ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #3498db;">
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Tổng học sinh</div>
                <div style="font-size: 32px; font-weight: bold; color: #3498db;"><?php echo $tongHS; ?></div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #28a745;">
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Đã nộp</div>
                <div style="font-size: 32px; font-weight: bold; color: #28a745;"><?php echo $daNop; ?></div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #ffc107;">
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Chưa chấm</div>
                <div style="font-size: 32px; font-weight: bold; color: #ffc107;"><?php echo $chuaCham; ?></div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #dc3545;">
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Chưa nộp</div>
                <div style="font-size: 32px; font-weight: bold; color: #dc3545;"><?php echo $chuaNop; ?></div>
            </div>
        </div>

        <!-- Bảng danh sách học sinh -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="background: #3498db; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;"><i class="fas fa-list-check"></i> Danh sách học sinh và bài nộp</h5>
                <button onclick="exportToExcel()" style="background: white; color: #3498db; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </button>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">STT</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Họ và tên</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Số báo danh</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Trạng thái</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Ngày nộp</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Điểm</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Nhận xét</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stt = 1;
                        $baiNopArray = [];
                        foreach($baiNopList as $bn) {
                            $baiNopArray[$bn['maHS']] = $bn;
                        }
                        
                        foreach($hocSinhList as $hs): 
                            $baiNop = $baiNopArray[$hs['maHS']] ?? null;
                        ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;"><?php echo $stt++; ?></td>
                                <td style="padding: 12px;">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($hs['hoVaTen']); ?>
                                </td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($hs['soBaoDanh']); ?></td>
                                <td style="padding: 12px;">
                                    <?php if($baiNop): ?>
                                        <?php if($baiNop['trangThai'] == 'DaCham'): ?>
                                            <span style="background: #28a745; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                                <i class="fas fa-check-circle"></i> Đã chấm
                                            </span>
                                        <?php elseif($baiNop['trangThai'] == 'DaNop'): ?>
                                            <span style="background: #ffc107; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                                <i class="fas fa-clock"></i> Chưa chấm
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="background: #dc3545; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                            <i class="fas fa-times-circle"></i> Chưa nộp
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px;">
                                    <?php echo $baiNop ? date('d/m/Y H:i', strtotime($baiNop['ngayNop'])) : '-'; ?>
                                </td>
                                <td style="padding: 12px;">
                                    <?php if($baiNop && $baiNop['trangThai'] == 'DaCham'): ?>
                                        <span style="font-size: 18px; font-weight: bold; color: #28a745;">
                                            <?php echo number_format($baiNop['diem'], 1); ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; max-width: 200px;">
                                    <?php 
                                    if($baiNop && $baiNop['nhanXet']) {
                                        $nhanXet = htmlspecialchars($baiNop['nhanXet']);
                                        echo mb_substr($nhanXet, 0, 50, 'UTF-8');
                                        if(mb_strlen($nhanXet, 'UTF-8') > 50) echo '...';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <?php if($baiNop): ?>
                                        <div style="display: flex; gap: 5px; justify-content: center;">
                                            <a href="index.php?controller=assignmentTeacher&action=submissionDetail&id=<?php echo $baiNop['maBaoCao']; ?>" 
                                               style="background: #17a2b8; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block;">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <button onclick='chamDiemNhanh(<?php echo json_encode($baiNop["maBaoCao"]); ?>, <?php echo json_encode($hs["hoVaTen"]); ?>, <?php echo json_encode($baiNop["noiDung"] ?? ""); ?>, <?php echo json_encode($baiNop["diem"] ?? 0); ?>, <?php echo json_encode($baiNop["nhanXet"] ?? ""); ?>)' 
                                                    style="background: #3498db; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                                <i class="fas fa-edit"></i> Chấm
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <button disabled style="background: #6c757d; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: not-allowed; font-size: 13px;">
                                            <i class="fas fa-times-circle"></i> Chưa có bài
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal chấm điểm nhanh -->
<div id="chamDiemModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 10px; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 30px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0;">
            <h5 style="margin: 0; font-size: 20px;"><i class="fas fa-edit"></i> Chấm điểm bài tập</h5>
        </div>
        <div style="padding: 25px;">
            <form id="formChamDiem">
                <input type="hidden" id="maBaoCao" name="maBaoCao">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                        <i class="fas fa-user"></i> Học sinh:
                    </label>
                    <p id="tenHocSinh" style="margin: 0; padding: 12px; background: #e8f4f8; border-radius: 6px; font-weight: 600; color: #2c3e50;"></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                        <i class="fas fa-file-alt"></i> Nội dung bài làm:
                    </label>
                    <div style="padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; max-height: 300px; overflow-y: auto; white-space: pre-wrap; line-height: 1.6;">
                        <p id="noiDungBai" style="margin: 0; color: #495057;"></p>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                            <i class="fas fa-star"></i> Điểm (0-10): <span style="color: red;">*</span>
                        </label>
                        <input type="number" id="diem" name="diem" min="0" max="10" step="0.25" required
                               style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 18px; font-weight: bold; text-align: center; color: #28a745;">
                        <small style="color: #6c757d; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Bước 0.25
                        </small>
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                            <i class="fas fa-comment"></i> Nhận xét:
                        </label>
                        <textarea id="nhanXet" name="nhanXet" rows="4"
                                  placeholder="Nhập nhận xét về bài làm của học sinh..."
                                  style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; resize: vertical; font-family: inherit;"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div style="padding: 15px 25px; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px; background: #f8f9fa; border-radius: 0 0 10px 10px;">
            <button onclick="closeChamDiemModal()" style="background: #6c757d; color: white; padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-times"></i> Hủy
            </button>
            <button onclick="saveChamDiem()" style="background: #28a745; color: white; padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-save"></i> Lưu điểm
            </button>
        </div>
    </div>
</div>

<script>
// Hàm chấm điểm nhanh (modal)
function chamDiemNhanh(maBaoCao, tenHS, noiDung, diem, nhanXet) {
    console.log('Data received:', {maBaoCao, tenHS, noiDung, diem, nhanXet}); // Debug
    
    document.getElementById('maBaoCao').value = maBaoCao;
    document.getElementById('tenHocSinh').textContent = tenHS;
    
    // Xử lý nội dung bài làm
    const noiDungElement = document.getElementById('noiDungBai');
    if (noiDung && noiDung.trim() !== '' && noiDung !== 'Em đã nộp bài') {
        noiDungElement.textContent = noiDung;
        noiDungElement.style.color = '#495057';
    } else {
        noiDungElement.innerHTML = '<em style="color: #999;"><i class="fas fa-info-circle"></i> Học sinh chưa nhập nội dung chi tiết bài làm</em>';
    }
    
    document.getElementById('diem').value = diem || '';
    document.getElementById('nhanXet').value = nhanXet || '';
    document.getElementById('chamDiemModal').style.display = 'flex';
}

function closeChamDiemModal() {
    document.getElementById('chamDiemModal').style.display = 'none';
}

function saveChamDiem() {
    const diem = parseFloat(document.getElementById('diem').value);
    
    if(isNaN(diem) || diem < 0 || diem > 10) {
        alert('Điểm không hợp lệ, vui lòng nhập từ 0-10');
        document.getElementById('diem').focus();
        return;
    }
    
    const formData = new FormData(document.getElementById('formChamDiem'));
    
    // Show loading
    const btnSave = event.target;
    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    
    fetch('index.php?controller=assignmentTeacher&action=saveChamDiem', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message);
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fas fa-save"></i> Lưu điểm';
        }
    })
    .catch(error => {
        alert('Có lỗi xảy ra, vui lòng thử lại');
        console.error('Error:', error);
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fas fa-save"></i> Lưu điểm';
    });
}

// Xuất Excel (placeholder)
function exportToExcel() {
    alert('Chức năng xuất Excel đang được phát triển...');
    // window.location.href = 'index.php?controller=assignmentTeacher&action=exportGrades&id=<?php echo $baiTap["maBaiTap"]; ?>';
}

// Đóng modal khi click outside
document.getElementById('chamDiemModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChamDiemModal();
    }
});

// Phím ESC để đóng modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeChamDiemModal();
    }
});
</script>

<style>
/* Hiệu ứng hover cho nút */
button:hover:not(:disabled) {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: all 0.2s;
}

button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Scroll bar cho modal */
#chamDiemModal > div {
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
}

#chamDiemModal > div::-webkit-scrollbar {
    width: 8px;
}

#chamDiemModal > div::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chamDiemModal > div::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#chamDiemModal > div::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<?php include 'views/layout/footer.php'; ?>