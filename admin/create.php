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
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $uploadResult = RequestHandler::handleFileUpload($_FILES['image']);
        if ($uploadResult['success']) {
            $imageName = $uploadResult['filename'];
        } else {
            $errors['image'] = $uploadResult['message'];
        }
    }
    
    if (empty($errors)) {
        $blog = new Blog();
        if ($blog->createPost($title, $description, $imageName)) {
            $success = 'Blog post created successfully!';
            // Clear form
            $_POST = [];
        } else {
            $errors['general'] = 'Failed to create blog post';
        }
    }
}

$pageTitle = 'Create New Post';
include '../includes/header.php';
?>

<?php renderNavigation('admin'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
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
                           value="<?= htmlspecialchars(RequestHandler::postRequest('title', '')) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php if (isset($errors['title'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['title']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                        Featured Image (JPG, JPEG, PNG)
                    </label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php if (isset($errors['image'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['image']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Blog Content *
                    </label>
                    <textarea id="description" name="description" rows="12" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars(RequestHandler::postRequest('description', '')) ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['description']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Create Post
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