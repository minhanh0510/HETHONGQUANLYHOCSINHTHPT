<?php
class Classroom {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll() {
        $sql = "SELECT maLop, tenLop, siSo, namHoc, maKhoi, maBan FROM LOP ORDER BY maKhoi, tenLop";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    
}