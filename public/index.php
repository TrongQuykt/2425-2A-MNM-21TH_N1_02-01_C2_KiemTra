<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$sinhViens = $sinhVienService->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Danh sách sinh viên</h1>
    <div class="button-group">
        <a href="create.php">Thêm sinh viên</a>
        <a href="hocphan.php">Đăng ký học phần</a>
        <a href="giohang.php">Xem giỏ hàng</a>
        <a href="login.php?logout=true">Đăng xuất</a>
    </div>
    <table>
        <tr>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Giới Tính</th>
            <th>Ngày Sinh</th>
            <th>Ngành Học</th>
            <th>Hình</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($sinhViens as $sv): ?>
        <tr>
            <td><?php echo $sv['MaSV']; ?></td>
            <td><?php echo $sv['HoTen']; ?></td>
            <td><?php echo $sv['GioiTinh']; ?></td>
            <td><?php echo $sv['NgaySinh']; ?></td>
            <td><?php echo $sv['TenNganh']; ?></td>
            <td>
                <?php if ($sv['Hinh'] && file_exists("../assets/images/" . $sv['Hinh'])): ?>
                    <img src="../assets/images/<?php echo urlencode($sv['Hinh']); ?>" width="50" alt="Hình sinh viên">
                <?php else: ?>
                    Không có hình
                <?php endif; ?>
            </td>
            <td>
                <a href="detail.php?maSV=<?php echo $sv['MaSV']; ?>">Chi tiết</a>
                <a href="edit.php?maSV=<?php echo $sv['MaSV']; ?>">Sửa</a>
                <a href="delete.php?maSV=<?php echo $sv['MaSV']; ?>" 
                   onclick="return confirm('Bạn có chắc muốn xóa?')" class="delete">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>