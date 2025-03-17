<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$nganhHocs = $sinhVienService->getAllNganhHoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'MaSV' => $_POST['MaSV'],
        'HoTen' => $_POST['HoTen'],
        'GioiTinh' => $_POST['GioiTinh'],
        'NgaySinh' => $_POST['NgaySinh'],
        'Hinh' => '', // Sẽ được xử lý trong service
        'MaNganh' => $_POST['MaNganh']
    ];
    if ($sinhVienService->create($data)) {
        header("Location: index.php");
    } else {
        echo "Thêm sinh viên thất bại!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm sinh viên</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Thêm sinh viên</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Mã SV:</label>
        <input type="text" name="MaSV" required>
        
        <label>Họ Tên:</label>
        <input type="text" name="HoTen" required>
        
        <label>Giới Tính:</label>
        <select name="GioiTinh">
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select>
        
        <label>Ngày Sinh:</label>
        <input type="date" name="NgaySinh" required>
        
        <label>Hình:</label>
        <input type="file" name="Hinh" accept="image/*" required>
        
        <label>Ngành Học:</label>
        <select name="MaNganh">
            <?php foreach ($nganhHocs as $nganh): ?>
                <option value="<?php echo $nganh['MaNganh']; ?>"><?php echo $nganh['TenNganh']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <input type="submit" value="Thêm">
    </form>
    <a href="index.php">Quay lại</a>
</body>
</html>