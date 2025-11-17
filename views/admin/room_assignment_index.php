<?php
include "views/layout/header.php";
include "views/layout/sidebar_admin.php";
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
    <h2>🏠 Danh sách phòng thi</h2>
    <p>Chọn phòng thi để phân công học sinh.</p>

    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên phòng</th>
                <th>Số lượng hiện tại</th>
                <th>Số lượng tối đa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $index => $room): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($room['tenPhong']) ?></td>
                    <td><?= htmlspecialchars($room['soLuongHienTai']) ?></td>
                    <td><?= htmlspecialchars($room['soLuongToiDa']) ?></td>
                    <td>
                        <a href="index.php?controller=roomAssignment&action=assign&roomId=<?= $room['maPhong'] ?>" class="btn btn-primary">Phân công</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include "views/layout/footer.php"; ?>