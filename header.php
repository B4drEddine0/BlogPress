<header class="bg-white shadow-sm">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
        <div class="flex lg:flex-1">
            <a href="index.php" class="-m-1.5 p-1.5">
                <span class="text-2xl font-bold text-indigo-600">BlogPress</span>
            </a>
        </div>
        <div class="flex lg:gap-x-12">
            <a href="index.php" class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600">Home</a>
            <a href="#" class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600">About</a>
            <a href="#" class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600">Contact</a>
        </div>
        <div class="flex lg:flex-1 lg:justify-end">
            <?php if(isset($_SESSION['Author_id'])): ?>
                <a href="admin.php" class="text-sm font-semibold leading-6 text-gray-900 mr-4 hover:text-indigo-600">Dashboard</a>
            <?php else: ?>
                <a href="login.php" class="text-sm font-semibold leading-6 text-gray-900 mr-4 hover:text-indigo-600">Log in</a>
                <a href="signUP.php" class="text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600">Sign up</a>
            <?php endif; ?>
        </div>
    </nav>
</header> 