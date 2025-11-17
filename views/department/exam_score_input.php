<?php
include "views/layout/header.php";
include "views/layout/sidebar_department.php";
?>

<style>
/* exam_score_input.css */

/* Tổng thể */
.main-content {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tiêu đề */
.main-content h2 {
    margin-bottom: 10px;
    color: #333;
}

/* Bảng */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.data-table th, .data-table td {
    padding: 12px 15px;
    text-align: left;
}

.data-table th {
    background-color: #007bff;
    color: #fff;
    font-weight: 600;
}

.data-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.data-table tr:hover {
    background-color: #e6f0ff;
}

/* Input fields */
.data-table input[type="number"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s ease-in-out;
}

.data-table input[type="number"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* Alerts */
.alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Nút */
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s ease-in-out;
    font-size: 14px;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}
</style>

<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<div class="main-content">
    <h2>📊 Nhập điểm tuyển sinh</h2>
    <p>Nhập điểm cho học sinh trong phòng thi.</p>

    <form method="POST" action="index.php?controller=examScore&action=save">
    <input type="hidden" name="roomId" value="<?= htmlspecialchars($_GET['roomId'] ?? $roomId ?? '') ?>">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên học sinh</th>
                <th>Điểm tuyển sinh</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $index => $student): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($student['hoVaTen']) ?></td>
                    <td>
                        <input type="number" name="scores[<?= $student['maHS'] ?>]" min="0" max="10" step="0.1"
                               value="<?= $student['diemTuyenSinh'] ?? '' ?>" required>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit" class="btn btn-success">💾 Lưu điểm</button>
</form>

</div>

<?php include "views/layout/footer.php"; ?>

<style>
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
</style>

<!-- JS Toast -->
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