<?php
function renderNavigation($currentPage = '') {
?>
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="index.php" class="flex-shrink-0 flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Blog</h1>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" 
                   class="<?= $currentPage === 'home' ? 'text-blue-600' : 'text-gray-600' ?> hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                    Home
                </a>
                <a href="admin/" 
                   class="<?= $currentPage === 'admin' ? 'text-blue-600' : 'text-gray-600' ?> hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                    Admin
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="index.php" class="block px-3 py-2 text-gray-600 hover:text-blue-600">Home</a>
            <a href="admin/" class="block px-3 py-2 text-gray-600 hover:text-blue-600">Admin</a>
        </div>
    </div>
</nav>

<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
});
</script>
<?php
}
?>