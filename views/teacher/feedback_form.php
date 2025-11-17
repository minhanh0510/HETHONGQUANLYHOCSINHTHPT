<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_teacher.php"; ?>
<style>
    /* Styling textarea đẹp */
    /* Giãn rộng 2 cột textarea */
.data-table td:nth-child(3),
.data-table th:nth-child(3) {
    width: 35%;
}

.data-table td:nth-child(4),
.data-table th:nth-child(4) {
    width: 35%;
}

.data-table textarea {
    width: 100%;
    min-height: 60px;
    resize: vertical;
    padding: 10px 12px;
    border: 1px solid #d0d7de;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #fafafa;
    transition: all 0.2s ease-in-out;
    box-sizing: border-box;
}

/* Khi hover */
.data-table textarea:hover {
    background: #f0f4ff;
    border-color: #9bbcf5;
}

/* Khi focus */
.data-table textarea:focus {
    background: #fff;
    border-color: #006aff;
    box-shadow: 0 0 0 3px rgba(0, 106, 255, 0.2);
    outline: none;
}

/* CSS toast */
.toast {
    min-width: 250px;
    margin-bottom: 10px;
    padding: 15px 20px;
    border-radius: 8px;
    color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease;
    font-family: sans-serif;
}
.toast.show {
    opacity: 1;
    transform: translateX(0);
}
.toast.success {
    background-color: #4CAF50;
}
.toast.error {
    background-color: #f44336;
}

/* Main table & button */
.main-content {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.main-content h2 { margin-bottom: 10px; color: #333; }
.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
.data-table th, .data-table td { padding: 12px 15px; text-align: left; }
.data-table th { background-color: #007bff; color: #fff; font-weight: 600; }
.data-table tr:nth-child(even) { background-color: #f2f2f2; }
.data-table tr:hover { background-color: #e6f0ff; }
.btn { padding: 6px 14px; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s ease-in-out; }
.btn-primary { background-color: #007bff; color: white; }
.btn-primary:hover { background-color: #0056b3; }
</style>
<!-- Container cho toast -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<div class="main-content">
    <h2>📝 Nhận xét & đánh giá học sinh</h2>
    <p>Nhập nhận xét cho tất cả học sinh trong lớp.</p>

    <form method="POST">
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>Nhận xét</th>
                    <th>Đánh giá</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($student['hoVaTen']) ?></td>
                            <td>
                                <textarea name="students[<?= $student['maHS'] ?>][nhanXet]" required></textarea>
                            </td>
                            <td>
                                <textarea name="students[<?= $student['maHS'] ?>][danhGia]" required></textarea>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Không có học sinh nào trong lớp.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">💾 Lưu toàn bộ</button>
    </form>
</div>

<?php include "views/layout/footer.php"; ?>

<script>
function showToast(message, type = 'success', duration = 2000) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = message;
    container.appendChild(toast);

    // Hiển thị toast
    setTimeout(() => toast.classList.add('show'), 100);

    // Tự động ẩn
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => container.removeChild(toast), 500);
    }, duration);
}

// Hiển thị thông báo từ PHP
<?php if (isset($_SESSION['success'])): ?>
    showToast("<?= $_SESSION['success'] ?>", 'success');
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    showToast("<?= $_SESSION['error'] ?>", 'error');
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
</script>