<?php
session_start();
require_once '../services/SinhVienService.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSV = $_POST['maSV'];
    $sinhVienService = new SinhVienService();
    $sinhVien = $sinhVienService->getById($maSV);
    if ($sinhVien) {
        $_SESSION['user'] = $maSV;
        header("Location: index.php");
    } else {
        echo "<script>alert('Mã sinh viên không tồn tại!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Đăng nhập</h1>
    <form method="POST">
        <label>Mã SV:</label>
        <input type="text" name="maSV" required>
        <input type="submit" value="Đăng nhập">
    </form>
</body>
</html>