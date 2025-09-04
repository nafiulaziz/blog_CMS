<?php
session_start();
require_once '../config/database.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';
require_once '../src/Blog.php';
require_once '../src/RequestHandler.php';

use LH\Auth;
use LH\Blog;
use LH\RequestHandler;

Auth::requireAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = RequestHandler::postRequest('id');
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
        exit;
    }
    
    $blog = new Blog();
    $post = $blog->getPostById($id);
    
    if (!$post) {
        echo json_encode(['success' => false, 'message' => 'Post not found']);
        exit;
    }
    
    // Delete image file if exists
    if ($post['image'] && file_exists('../uploads/' . $post['image'])) {
        unlink('../uploads/' . $post['image']);
    }
    
    if ($blog->deletePost($id)) {
        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}