<?php 
// views/teacher/assignment/create.php
include 'views/layout/header.php'; 
include 'views/layout/sidebar_teacher.php'; 
?>

<main class="main-content">
    <div class="py-4">
        <nav style="margin-bottom: 20px;">
            <a href="index.php?controller=assignmentTeacher&action=index" style="color: #3498db; text-decoration: none;">Bài tập</a>
            <span style="color: #7f8c8d;"> / </span>
            <span style="color: #7f8c8d;">Tạo mới</span>
        </nav>
        
        <h2 style="margin-bottom: 20px; color: #2c3e50;">
            <i class="fas fa-plus-circle"></i> Tạo bài tập/kiểm tra mới
        </h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <form action="index.php?controller=assignmentTeacher&action=store" method="POST" enctype="multipart/form-data" id="formCreateAssignment">
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <!-- Tên bài tập -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Tên bài tập/kiểm tra <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="tenBaiTap" id="tenBaiTap" required
                               placeholder="VD: Bài tập Chương 1 - Hàm số"
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Lớp -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Chọn lớp <span style="color: red;">*</span>
                            </label>
                            <select name="maLop" id="maLop" required
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                <option value="">-- Chọn lớp --</option>
                                <?php foreach($lopList as $lop): ?>
                                    <option value="<?php echo $lop['maLop']; ?>" data-mamon="<?php echo $lop['maMon']; ?>">
                                        <?php echo htmlspecialchars($lop['tenLop'] . ' - ' . $lop['tenMon']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Môn học -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                Môn học <span style="color: red;">*</span>
                            </label>
                            <select name="maMon" id="maMon" required
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                <option value="">-- Chọn môn --</option>
                                <?php foreach($monList as $mon): ?>
                                    <option value="<?php echo $mon['maMon']; ?>">
                                        <?php echo htmlspecialchars($mon['tenMon']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Thời hạn nộp -->
                    <div style="max-width: 50%;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Thời hạn nộp <span style="color: red;">*</span>
                        </label>
                        <input type="datetime-local" name="thoiHanNop" id="thoiHanNop" required
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>

                    <!-- Mô tả -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Mô tả ngắn
                        </label>
                        <textarea name="moTa" id="moTa" rows="2"
                                  placeholder="Mô tả ngắn gọn về bài tập"
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
                    </div>

                    <!-- Nội dung -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            Nội dung chi tiết
                        </label>
                        <textarea name="noiDung" id="noiDung" rows="5"
                                  placeholder="Nhập nội dung chi tiết của bài tập, yêu cầu cần thực hiện..."
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
                        <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Bạn có thể định dạng văn bản hoặc thêm link tài liệu
                        </small>
                    </div>

                    <!-- File đính kèm -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                            <i class="fas fa-paperclip"></i> Tài liệu đính kèm
                        </label>
                        <div id="dropArea" style="border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center; background: #f8f9fa; transition: all 0.3s ease;">
                            <input type="file" name="taiLieuDinhKem[]" id="taiLieuDinhKem" multiple 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar"
                                   style="display: none;" onchange="displayFileNames()">
                            <label for="taiLieuDinhKem" style="cursor: pointer; color: #3498db;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #3498db; display: block; margin-bottom: 10px;"></i>
                                <span style="font-size: 16px; font-weight: 500;">Nhấp để chọn file hoặc kéo thả file vào đây</span>
                            </label>
                            <p style="margin: 10px 0 0 0; color: #7f8c8d; font-size: 13px;">
                                Hỗ trợ: PDF, Word, Excel, PowerPoint, hình ảnh, ZIP (Tối đa 10MB/file)
                            </p>
                        </div>
                        <div id="fileList" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; justify-content: space-between; padding-top: 20px; border-top: 1px solid #e9ecef;">
                        <a href="index.php?controller=assignmentTeacher&action=index" 
                           style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-save"></i> Tạo bài tập
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Set minimum datetime to now
const now = new Date();
now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
document.getElementById('thoiHanNop').min = now.toISOString().slice(0, 16);

// Auto-select môn when lớp is selected
document.getElementById('maLop').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const maMon = selectedOption.getAttribute('data-mamon');
    if(maMon) {
        document.getElementById('maMon').value = maMon;
    }
});

// Display selected file names with remove button
function displayFileNames() {
    const fileInput = document.getElementById('taiLieuDinhKem');
    const fileList = document.getElementById('fileList');
    const files = fileInput.files;
    
    if (files.length > 0) {
        let html = '<div style="background: #e8f4f8; padding: 15px; border-radius: 5px; border-left: 4px solid #3498db;">';
        html += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
        html += '<strong style="color: #2c3e50;"><i class="fas fa-file"></i> Đã chọn ' + files.length + ' file:</strong>';
        html += '<button type="button" onclick="clearAllFiles()" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">';
        html += '<i class="fas fa-trash"></i> Xóa tất cả</button>';
        html += '</div>';
        html += '<div style="max-height: 200px; overflow-y: auto;">';
        
        for (let i = 0; i < files.length; i++) {
            const fileSize = (files[i].size / 1024).toFixed(2);
            const fileSizeDisplay = fileSize > 1024 ? (fileSize / 1024).toFixed(2) + ' MB' : fileSize + ' KB';
            const fileIcon = getFileIcon(files[i].name);
            
            html += '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: white; margin-bottom: 5px; border-radius: 4px;">';
            html += '<div style="flex: 1;">';
            html += '<i class="' + fileIcon + '" style="color: #3498db; margin-right: 8px;"></i>';
            html += '<span style="color: #2c3e50;">' + files[i].name + '</span>';
            html += ' <span style="color: #7f8c8d; font-size: 12px;">(' + fileSizeDisplay + ')</span>';
            html += '</div>';
            html += '<button type="button" onclick="removeFile(' + i + ')" style="background: #e74c3c; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer; font-size: 11px;">';
            html += '<i class="fas fa-times"></i></button>';
            html += '</div>';
        }
        
        html += '</div></div>';
        fileList.innerHTML = html;
    } else {
        fileList.innerHTML = '';
    }
}

// Get appropriate icon based on file extension
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': 'fas fa-file-pdf',
        'doc': 'fas fa-file-word',
        'docx': 'fas fa-file-word',
        'xls': 'fas fa-file-excel',
        'xlsx': 'fas fa-file-excel',
        'ppt': 'fas fa-file-powerpoint',
        'pptx': 'fas fa-file-powerpoint',
        'jpg': 'fas fa-file-image',
        'jpeg': 'fas fa-file-image',
        'png': 'fas fa-file-image',
        'gif': 'fas fa-file-image',
        'zip': 'fas fa-file-archive',
        'rar': 'fas fa-file-archive',
        'txt': 'fas fa-file-alt'
    };
    return icons[ext] || 'fas fa-file';
}

// Remove individual file
function removeFile(index) {
    const fileInput = document.getElementById('taiLieuDinhKem');
    const dt = new DataTransfer();
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    fileInput.files = dt.files;
    displayFileNames();
}

// Clear all files
function clearAllFiles() {
    const fileInput = document.getElementById('taiLieuDinhKem');
    fileInput.value = '';
    displayFileNames();
}

// Drag and drop functionality
const dropArea = document.getElementById('dropArea');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => {
        dropArea.style.borderColor = '#3498db';
        dropArea.style.background = '#e8f4f8';
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => {
        dropArea.style.borderColor = '#ddd';
        dropArea.style.background = '#f8f9fa';
    }, false);
});

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('taiLieuDinhKem').files = files;
    displayFileNames();
}

// Form validation
document.getElementById('formCreateAssignment').addEventListener('submit', function(e) {
    const tenBaiTap = document.getElementById('tenBaiTap').value.trim();
    const maLop = document.getElementById('maLop').value;
    const maMon = document.getElementById('maMon').value;
    const thoiHanNop = document.getElementById('thoiHanNop').value;
    
    if(!tenBaiTap) {
        e.preventDefault();
        alert('Vui lòng nhập tên bài tập');
        return false;
    }
    
    if(!maLop) {
        e.preventDefault();
        alert('Vui lòng chọn lớp');
        return false;
    }
    
    if(!maMon) {
        e.preventDefault();
        alert('Vui lòng chọn môn học');
        return false;
    }
    
    if(!thoiHanNop) {
        e.preventDefault();
        alert('Vui lòng chọn thời hạn nộp');
        return false;
    }
    
    // Validate file size
    const fileInput = document.getElementById('taiLieuDinhKem');
    const files = fileInput.files;
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > maxSize) {
            e.preventDefault();
            alert('File "' + files[i].name + '" vượt quá giới hạn 10MB. Vui lòng chọn file nhỏ hơn.');
            return false;
        }
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>