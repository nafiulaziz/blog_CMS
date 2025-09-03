<?php
namespace LH;

class Settings 
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
        $this->initializeSettings();
    }

    private function initializeSettings() 
    {
        // Create settings table if not exists
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(255) UNIQUE,
            setting_value VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);

        // Insert default posts per page if not exists
        $sql = "INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('posts_per_page', '10')";
        $this->db->exec($sql);
    }

    public function get($key, $default = null) 
    {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = :key";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result ? $result['setting_value'] : $default;
    }

    public function set($key, $value) 
    {
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':key', $key);
        $stmt->bindValue(':value', $value);
        return $stmt->execute();
    }
}