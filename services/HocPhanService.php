<?php
require_once '../config/database.php';

class HocPhanService {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM HocPhan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($maHP) {
        $query = "SELECT * FROM HocPhan WHERE MaHP = :MaHP";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['MaHP' => $maHP]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function decreaseQuantity($maHP) {
        $query = "UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien - 1 WHERE MaHP = :MaHP AND SoLuongDuKien > 0";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['MaHP' => $maHP]);
    }
}
?>