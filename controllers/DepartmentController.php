<?php
class DepartmentController {
    private $db;
    public function __construct($db) { 
        $this->db = $db; 
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        include "views/department/dashboard.php";
    }
}