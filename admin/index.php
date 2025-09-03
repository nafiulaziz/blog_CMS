<?php
session_start();
require_once '../config/database.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';
require_once '../src/Blog.php';
require_once '../includes/nav.php';

use LH\Auth;
use LH\Blog;

Auth::requireAuth();

$blog = new Blog();
$posts = $blog->getAllPosts(100); // Get all posts for admin

$pageTitle = 'Admin Dashboard';
include '../includes/header.php';
?>

<?php renderNavigation('admin', true); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <div class="space-x-4">
            <a href="create.php" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Create New Post
            </a>
            <a href="settings.php" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Settings
            </a>
            <a href="logout.php" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                Logout
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Blog Posts</h2>
        </div>
        
        <?php if (empty($posts)): ?>
            <div class="p-6 text-center">
                <p class="text-gray-500 mb-4">No blog posts yet.</p>
                <a href="create.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Create Your First Post
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($posts as $post): ?>
                            <tr class="hover:bg-gray-50" data-post-id="<?= $post['id'] ?>">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars(substr($post['description'], 0, 100)) ?>...
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($post['image']): ?>
                                        <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" 
                                             alt="Post image" class="h-12 w-12 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium space-x-2">
                                    <a href="edit.php?id=<?= $post['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <button onclick="deletePost(<?= $post['id'] ?>)" 
                                            class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this blog post? This action cannot be undone.</p>
        <div class="flex space-x-4">
            <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Delete
            </button>
            <button id="cancelDelete" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Cancel
            </button>
        </div>
    </div>
</div>

<script src="../assets/js/admin.js"></script>

<?php include '../includes/footer.php'; ?>