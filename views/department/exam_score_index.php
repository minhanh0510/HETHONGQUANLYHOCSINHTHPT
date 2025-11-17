<?php
include "views/layout/header.php";
include "views/layout/sidebar_department.php";
?>

<style>
/* exam_score_index.css */

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

/* Nút */
.btn {
    padding: 6px 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s ease-in-out;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

</style>

<div class="main-content">
    <h2>📊 Nhập điểm tuyển sinh</h2>
    <p>Chọn trường để nhập điểm tuyển sinh.</p>

    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên trường</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($schools as $index => $school): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($school['tenTruong']) ?></td>
                    <td>
                        <a href="index.php?controller=examScore&action=rooms&schoolId=<?= $school['maTruong'] ?>" class="btn btn-primary">Chọn</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
