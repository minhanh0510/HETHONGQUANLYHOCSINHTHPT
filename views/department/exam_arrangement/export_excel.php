<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=danh-sach-thi-" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>DANH SÁCH THI TUYỂN SINH</h2>
    <p>Ngày xuất: <?php echo date('d/m/Y H:i:s'); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Số báo danh</th>
                <th>Họ và tên</th>
                <th>Phòng thi</th>
                <th>Trường</th>
                <th>Môn thi</th>
                <th>Ngày thi</th>
                <th>Ca thi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($list as $item): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $item['soBaoDanh']; ?></td>
                <td><?php echo $item['hoVaTen']; ?></td>
                <td><?php echo $item['tenPhong']; ?></td>
                <td><?php echo $item['tenTruong']; ?></td>
                <td><?php echo $item['tenMon']; ?></td>
                <td><?php echo $item['ngayThi']; ?></td>
                <td><?php echo $item['caThi']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>