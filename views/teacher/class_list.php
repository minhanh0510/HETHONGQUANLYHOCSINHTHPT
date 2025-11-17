<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_teacher.php"; ?>
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
    <h2>📚 Danh sách lớp</h2>
    <p>Chọn lớp để nhập nhận xét học sinh.</p>

    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã lớp</th>
                <th>Tên lớp</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $index => $class): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($class['maLop']) ?></td>
                    <td><?= htmlspecialchars($class['tenLop']) ?></td>
                    <td>
                        <a href="index.php?controller=feedbackEvaluation&action=showStudents&classId=<?= $class['maLop'] ?>"
                           class="btn btn-primary">Xem học sinh</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "views/layout/footer.php"; ?>
