<?php
namespace LH;

use PDO;

class Blog 
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPosts($limit = 10, $offset = 0) 
    {
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPostById($id) 
    {
        $sql = "SELECT * FROM blogs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getTotalPosts() 
    {
        $sql = "SELECT COUNT(*) as total FROM blogs";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'];
    }

    public function createPost($title, $description, $image) 
    {
        $sql = "INSERT INTO blogs (title, description, image) VALUES (:title, :description, :image)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        return $stmt->execute();
    }

    public function updatePost($id, $title, $description, $image = null) 
    {
        if ($image) {
            $sql = "UPDATE blogs SET title = :title, description = :description, image = :image WHERE id = :id";
        } else {
            $sql = "UPDATE blogs SET title = :title, description = :description WHERE id = :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        
        if ($image) {
            $stmt->bindValue(':image', $image);
        }
        
        return $stmt->execute();
    }

    public function deletePost($id) 
    {
        $sql = "DELETE FROM blogs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}