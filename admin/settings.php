<?php
session_start();
require_once '../config/database.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';
require_once '../src/Settings.php';
require_once '../src/RequestHandler.php';
require_once '../includes/nav.php';

use LH\Auth;
use LH\Settings;
use LH\RequestHandler;

Auth::requireAuth();

$settings = new Settings();
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postsPerPage = RequestHandler::postRequest('posts_per_page');
    
    if (!is_numeric($postsPerPage) || $postsPerPage < 1 || $postsPerPage > 50) {
        $errors['posts_per_page'] = 'Posts per page must be a number between 1 and 50';
    } else {
        if ($settings->set('posts_per_page', $postsPerPage)) {
            $success = 'Settings updated successfully!';
        } else {
            $errors['general'] = 'Failed to update settings';
        }
    }
}

$currentPostsPerPage = $settings->get('posts_per_page', 10);

$pageTitle = 'Admin Settings';
include '../includes/header.php';
?>

<?php renderNavigation('admin'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
        <a href="index.php" class="text-blue-600 hover:text-blue-800">← Back to Dashboard</a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form method="POST" class="p-6">
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
                    <label for="posts_per_page" class="block text-sm font-medium text-gray-700 mb-1">
                        Posts Per Page
                    </label>
                    <select id="posts_per_page" name="posts_per_page"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <?php for ($i = 5; $i <= 25; $i += 5): ?>
                            <option value="<?= $i ?>" <?= $currentPostsPerPage == $i ? 'selected' : '' ?>>
                                <?= $i ?> posts
                            </option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($errors['posts_per_page'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['posts_per_page']) ?></p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500">Number of blog posts to display on each page</p>
                </div>

                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>