<?php
include "views/layout/header.php";
include "views/layout/sidebar_admin.php";
?>

<!-- Container cho toast -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
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

<div class="main-content">
    <h2>📋 Phân công học sinh vào phòng thi</h2>
    <p>Chọn học sinh để gán vào phòng thi.</p>

    <form method="POST" action="index.php?controller=roomAssignment&action=save">
        <input type="hidden" name="roomId" value="<?= htmlspecialchars($_GET['roomId']) ?>">
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($student['hoVaTen']) ?></td>
                        <td>
                            <button type="submit" name="studentId" value="<?= $student['maHS'] ?>" class="btn btn-primary">Gán vào phòng</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

    <?php
    // Lấy redirect từ URL nếu có
    $redirect = $_GET['redirect'] ?? null;
    unset($_SESSION['error']);
    ?>

    <?php if ($redirect): ?>
    // Tự động redirect sau thời gian toast
    setTimeout(() => {
        window.location.href = "index.php?controller=roomAssignment&action=<?= $redirect ?>";
    }, 2000);
    <?php endif; ?>
<?php endif; ?>
</script>
