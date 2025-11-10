<?php
session_start();
require_once 'config/database.php';
require_once 'src/Database.php';
require_once 'src/Blog.php';
require_once 'src/RequestHandler.php';
require_once 'src/Settings.php';
require_once 'includes/nav.php';

use LH\Blog;
use LH\RequestHandler;
use LH\Settings;

$blog = new Blog();
$settings = new Settings();


$postsPerPage = (int)$settings->get('posts_per_page', 10);
$currentPage = (int)RequestHandler::getRequest('page', 1);
$offset = ($currentPage - 1) * $postsPerPage;

$posts = $blog->getAllPosts($postsPerPage, $offset);
$totalPosts = $blog->getTotalPosts();
$totalPages = ceil($totalPosts / $postsPerPage);

$pageTitle = 'Home';
include 'includes/header.php';
?>

<?php renderNavigation('home'); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Blog</h1>
        <p class="text-xl text-gray-600">Discover amazing stories and insights</p>
        <p class="text-xl text-gray-600">Discover amazing stories and insights</p>
    </div>

    <?php if (empty($posts)): ?>
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg">No blog posts found.</div>
            <a href="admin/" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Create Your First Post
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php foreach ($posts as $post): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <?php if ($post['image']): ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="uploads/<?= htmlspecialchars($post['image']) ?>" 
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 class="w-full h-48 object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                            <?= htmlspecialchars($post['title']) ?>
                        </h2>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?= htmlspecialchars(substr($post['description'], 0, 150)) ?>
                            <?= strlen($post['description']) > 150 ? '...' : '' ?>
                        </p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                            </span>
                            <a href="blog.php?id=<?= $post['id'] ?>" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                                Read More
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="flex justify-center" aria-label="Pagination">
                <div class="flex space-x-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                            Previous
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="px-3 py-2 bg-blue-600 text-white rounded-md"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>" 
                               class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>