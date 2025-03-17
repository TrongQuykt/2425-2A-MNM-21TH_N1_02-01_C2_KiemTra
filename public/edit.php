<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$nganhHocs = $sinhVienService->getAllNganhHoc();

if (!isset($_GET['maSV'])) {
    header("Location: index.php");
    exit();
}

$sinhVien = $sinhVienService->getById($_GET['maSV']);
if (!$sinhVien) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'MaSV' => $_POST['MaSV'],
        'HoTen' => $_POST['HoTen'],
        'GioiTinh' => $_POST['GioiTinh'],
        'NgaySinh' => $_POST['NgaySinh'],
        'Hinh' => $sinhVien['Hinh'],
        'MaNganh' => $_POST['MaNganh']
    ];
    if ($sinhVienService->update($data)) {
        header("Location: index.php");
    } else {
        echo "Sửa sinh viên thất bại!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sinh viên</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Sửa sinh viên</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Mã SV:</label>
        <input type="text" name="MaSV" value="<?php echo $sinhVien['MaSV']; ?>" readonly>
        
        <label>Họ Tên:</label>
        <input type="text" name="HoTen" value="<?php echo $sinhVien['HoTen']; ?>" required>
        
        <label>Giới Tính:</label>
        <select name="GioiTinh">
            <option value="Nam" <?php if ($sinhVien['GioiTinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
            <option value="Nữ" <?php if ($sinhVien['GioiTinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
        </select>
        
        <label>Ngày Sinh:</label>
        <input type="date" name="NgaySinh" value="<?php echo $sinhVien['NgaySinh']; ?>" required>
        
        <label>Hình hiện tại:</label>
        <?php if ($sinhVien['Hinh'] && file_exists("../assets/images/" . $sinhVien['Hinh'])): ?>
            <img src="../assets/images/<?php echo urlencode($sinhVien['Hinh']); ?>" width="100" alt="Hình hiện tại"><br>
        <?php else: ?>
            Không có hình<br>
        <?php endif; ?>
        
        <label>Upload hình mới:</label>
        <input type="file" name="Hinh" accept="image/*">
        
        <label>Ngành Học:</label>
        <select name="MaNganh">
            <?php foreach ($nganhHocs as $nganh): ?>
                <option value="<?php echo $nganh['MaNganh']; ?>" 
                        <?php if ($nganh['MaNganh'] == $sinhVien['MaNganh']) echo 'selected'; ?>>
                    <?php echo $nganh['TenNganh']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="submit" value="Cập nhật">
    </form>