<?php
session_start();
require_once '../services/HocPhanService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$hocPhanService = new HocPhanService();
$hocPhans = $hocPhanService->getAll();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['maHP'])) {
    $maHP = $_POST['maHP'];
    if (!in_array($maHP, $_SESSION['cart'])) {
        $hocPhan = $hocPhanService->getById($maHP);
        if ($hocPhan['SoLuongDuKien'] > 0) {
            $_SESSION['cart'][] = $maHP;
        } else {
            echo "<script>alert('Học phần này đã hết chỗ!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký học phần</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Danh sách học phần</h1>
    <table>
        <tr>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
            <th>Số lượng dự kiến</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($hocPhans as $hp): ?>
        <tr>
            <td><?php echo $hp['MaHP']; ?></td>
            <td><?php echo $hp['TenHP']; ?></td>
            <td><?php echo $hp['SoTinChi']; ?></td>
            <td><?php echo $hp['SoLuongDuKien']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="maHP" value="<?php echo $hp['MaHP']; ?>">
                    <button type="submit">Thêm vào giỏ</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="giohang.php">Xem giỏ hàng</a>
    <a href="index.php">Quay lại</a>
</body>
</html>