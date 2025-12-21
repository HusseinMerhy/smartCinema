<?php
require_once 'Model.php';

class Snack extends Model {
    
    public function getAllSnacks() {
        // Prepare statement
        $stmt = $this->db->prepare("SELECT * FROM snacks");
        
        // Execute
        $stmt->execute();
        
        // Get result set
        $result = $stmt->get_result();
        
        // Fetch all rows as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSnackById($id) {
        $stmt = $this->db->prepare("SELECT * FROM snacks WHERE id = ?");
        
        // Bind parameter 'i' for integer
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        // Fetch single row
        return $result->fetch_assoc();
    }
}
?>