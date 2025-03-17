<?php
session_start();
require_once '../services/DangKyService.php';
require_once '../services/HocPhanService.php';
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Khởi tạo giỏ hàng nếu chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$dangKyService = new DangKyService();
$hocPhanService = new HocPhanService();
$sinhVienService = new SinhVienService();

// Lấy thông tin sinh viên
$sinhVien = $sinhVienService->getById($_SESSION['user']);
if (!$sinhVien) {
    echo "Không tìm thấy thông tin sinh viên!";
    exit();
}

// Xử lý lưu đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $maSV = $_SESSION['user'];
    if ($dangKyService->saveDangKy($maSV, $_SESSION['cart'])) {
        unset($_SESSION['cart']); // Xóa giỏ hàng sau khi lưu thành công
        echo "<script>alert('Đăng ký thành công!'); window.location.href='index.php';</script>";
        exit(); // Đảm bảo thoát sau khi chuyển hướng
    } else {
        echo "<script>alert('Đăng ký thất bại!');</script>";
    }
}

// Xử lý xóa từng học phần
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: giohang.php");
    exit();
}

// Xử lý xóa toàn bộ giỏ hàng
if (isset($_GET['delete_all'])) {
    unset($_SESSION['cart']);
    header("Location: giohang.php");
    exit();
}

// Lấy danh sách học phần đã đăng ký
$dangKyHocPhans = $dangKyService->getDangKyBySinhVien($_SESSION['user']);

// Tính toán số học phần và tổng số tín chỉ
$soHocPhan = count($_SESSION['cart']);
$tongSoTinChi = 0;
foreach ($_SESSION['cart'] as $maHP) {
    $hp = $hocPhanService->getById($maHP);
    if ($hp) {
        $tongSoTinChi += $hp['SoTinChi'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Giỏ hàng</h1>

    <!-- Hiển thị thông tin sinh viên -->
    <div class="sinh-vien-info">
        <h2>Thông tin sinh viên</h2>
        <table>
            <tr>
                <th>Mã SV</th>
                <td><?php echo $sinhVien['MaSV']; ?></td>
            </tr>
            <tr>
                <th>Họ Tên</th>
                <td><?php echo $sinhVien['HoTen']; ?></td>
            </tr>
            <tr>
                <th>Giới Tính</th>
                <td><?php echo $sinhVien['GioiTinh']; ?></td>
            </tr>
            <tr>
                <th>Ngày Sinh</th>
                <td><?php echo $sinhVien['NgaySinh']; ?></td>
            </tr>
            <tr>
                <th>Ngành Học</th>
                <td><?php echo $sinhVien['TenNganh']; ?></td>
            </tr>
        </table>
    </div>

    <!-- Hiển thị giỏ hàng -->
    <h2>Học phần trong giỏ hàng</h2>
    <table>
        <tr>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
            <th>Hành động</th>
        </tr>
        <?php 
        foreach ($_SESSION['cart'] as $index => $maHP) {
            $hp = $hocPhanService->getById($maHP);
            if ($hp) {
                echo "<tr>
                    <td>{$hp['MaHP']}</td>
                    <td>{$hp['TenHP']}</td>
                    <td>{$hp['SoTinChi']}</td>
                    <td>
                        <a href='giohang.php?delete=$index' class='delete'>Xóa</a>
                    </td>
                </tr>";
            }
        }
        ?>
    </table>

    <!-- Hiển thị số học phần và tổng số tín chỉ -->
    <div class="cart-summary">
        <p>Số học phần: <?php echo $soHocPhan; ?></p>
        <p>Tổng số tín chỉ: <?php echo $tongSoTinChi; ?></p>
    </div>

    <!-- Nút lưu đăng ký và xóa toàn bộ -->
    <form method="POST">
        <button type="submit" name="save">Lưu đăng ký</button>
    </form>
    <a href="giohang.php?delete_all=true" class="delete">Xóa toàn bộ</a>
    <a href="hocphan.php">Quay lại đăng ký</a>

    <!-- Hiển thị học phần đã đăng ký -->
    <h2>Học phần đã đăng ký</h2>
    <table>
        <tr>
            <th>Mã ĐK</th>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
        </tr>
        <?php foreach ($dangKyHocPhans as $dk): ?>
        <tr>
            <td><?php echo $dk['MaDK']; ?></td>
            <td><?php echo $dk['MaHP']; ?></td>
            <td><?php echo $dk['TenHP']; ?></td>
            <td><?php echo $dk['SoTinChi']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>