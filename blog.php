<?php
session_start();
require_once 'config/database.php';
require_once 'src/Database.php';
require_once 'src/Blog.php';
require_once 'src/RequestHandler.php';
require_once 'includes/nav.php';

use LH\Blog;
use LH\RequestHandler;

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

$pageTitle = $post['title'];
include 'includes/header.php';
?>

<?php renderNavigation(); ?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
        <?php if ($post['image']): ?>
            <div class="w-full h-64 md:h-96">
                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" 
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     class="w-full h-full object-cover">
            </div>
        <?php endif; ?>
        
        <div class="p-6 md:p-8">
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                <div class="text-gray-500 text-sm">
                    Published on <?= date('F j, Y', strtotime($post['created_at'])) ?>
                </div>
            </div>
            
            <div class="prose prose-lg max-w-none">
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    <?= htmlspecialchars($post['description']) ?>
                </div>
            </div>
        </div>
    </article>

    <div class="mt-8 text-center">
        <a href="index.php" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Blog
        </a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>