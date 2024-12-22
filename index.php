<?php 
session_start();
include ('connexion.php'); 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress</title>
</head>
<body class="flex flex-col min-h-screen">
    <?php include('header.php'); ?>

    <main class="flex-grow">
        <section class="bg-white dark:bg-gray-900">
            <div class="text-center py-10">
                <h1 class="text-4xl font-bold text-black dark:text-white mb-4">Discover New Adventures</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">Explore, discover, and find inspiration through these exciting journeys.</p>
            </div>

            <div class="px-8 py-10 mx-auto lg:max-w-screen-xl sm:max-w-xl md:max-w-full sm:px-12 md:px-16 lg:py-20 sm:py-16">
                <div class="grid gap-x-8 gap-y-12 sm:gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                    <?php 
                    $query = "SELECT a.id_art, a.create_dat, a.title, a.content, a.views
                                FROM article a
                                ORDER BY a.views DESC";
                    $result = mysqli_query($conn, $query);
                    ?>
                    <?php while($article = mysqli_fetch_assoc($result)) { ?>
                        <div class="relative">
                            <a href="details.php?id=<?php echo $article['id_art']; ?>" class="block overflow-hidden group rounded-xl shadow-lg relative">
                                <img src="images/blog-cover.jpg" class="object-cover w-full h-56 transition-all duration-300 ease-out sm:h-64 group-hover:scale-110" alt="Adventure">
                                <?php if($article['views'] > 50): ?>
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Popular
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="relative mt-5">
                                <p class="uppercase font-semibold text-xs mb-2.5 text-purple-600">
                                    <?php echo date('F jS Y', strtotime($article['create_dat'])); ?>
                                </p>
                                <a href="details.php?id=<?php echo $article['id_art']; ?>" class="block mb-3 hover:underline">
                                    <h2 class="text-2xl font-bold leading-5 text-black dark:text-white transition-colors duration-200 hover:text-purple-700 dark:hover:text-purple-400">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h2>
                                </a>
                                <p class="mb-4 text-gray-700 dark:text-gray-300">
                                    <?php echo htmlspecialchars(substr($article['content'], 0, 15)) . '...'; ?>
                                </p>
                                <div class="flex items-center justify-between">
                                    <a href="details.php?id=<?php echo $article['id_art']; ?>" 
                                       class="font-medium underline text-purple-600 dark:text-purple-400">
                                        Read More
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <?php echo $article['views']; ?> views
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>