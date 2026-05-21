<?php
class BaseModel {
    protected $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }
    
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function querySingle($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    protected function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    protected function commit() {
        return $this->db->commit();
    }
    
    protected function rollBack() {
        return $this->db->rollBack();
    }
}
?>