<?php
session_start();
require_once '../config/database.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';
require_once '../src/Blog.php';
require_once '../src/RequestHandler.php';
require_once '../includes/nav.php';

use LH\Auth;
use LH\Blog;
use LH\RequestHandler;

Auth::requireAuth();

$id = RequestHandler::getRequest('id');
if (!$id) {
    header('Location: index.php');
    exit;
}

$blog = new Blog();
$post = $blog->getPostById($id);

if (!$post) {
    header('Location: index.php');
    exit;
}

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = RequestHandler::postRequest('title');
    $description = RequestHandler::postRequest('description');
    
    // Validate input
    $validationRules = [
        'title' => ['required' => true, 'min_length' => 3, 'max_length' => 255],
        'description' => ['required' => true, 'min_length' => 10]
    ];
    
    $errors = RequestHandler::validate($_POST, $validationRules);
    
    // Handle file upload
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadResult = RequestHandler::handleFileUpload($_FILES['image']);
        if ($uploadResult['success']) {
            $imageName = $uploadResult['filename'];
            // Delete old image if exists
            if ($post['image'] && file_exists('../uploads/' . $post['image'])) {
                unlink('../uploads/' . $post['image']);
            }
        } else {
            $errors['image'] = $uploadResult['message'];
        }
    }
    
    if (empty($errors)) {
        if ($blog->updatePost($id, $title, $description, $imageName)) {
            $success = 'Blog post updated successfully!';
            // Refresh post data
            $post = $blog->getPostById($id);
        } else {
            $errors['general'] = 'Failed to update blog post';
        }
    }
}

$pageTitle = 'Edit Post';
include '../includes/header.php';
?>

<?php renderNavigation('admin'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
        <a href="index.php" class="text-blue-600 hover:text-blue-800">← Back to Dashboard</a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Blog Title *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="<?= htmlspecialchars($post['title']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php if (isset($errors['title'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['title']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                        Featured Image (JPG, JPEG, PNG)
                    </label>
                    <?php if ($post['image']): ?>
                        <div class="mb-3">
                            <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" 
                                 alt="Current image" class="h-24 w-24 object-cover rounded">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php if (isset($errors['image'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['image']) ?></p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Blog Content *
                    </label>
                    <textarea id="description" name="description" rows="12" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($post['description']) ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['description']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Post
                    </button>
                    <a href="index.php" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>