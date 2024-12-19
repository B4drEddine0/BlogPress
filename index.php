<?php include ('connexion.php'); ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress</title>
</head>
<body>
<section class="bg-white dark:bg-gray-900">
  <div class="text-center py-10">
    <h1 class="text-4xl font-bold text-black dark:text-white mb-4">Discover New Adventures</h1>
    <p class="text-lg text-gray-600 dark:text-gray-400">Explore, discover, and find inspiration through these exciting journeys.</p>
  </div>

  <div class="px-8 py-10 mx-auto lg:max-w-screen-xl sm:max-w-xl md:max-w-full sm:px-12 md:px-16 lg:py-20 sm:py-16">
    <div class="grid gap-x-8 gap-y-12 sm:gap-y-16 md:grid-cols-2 lg:grid-cols-3">
      <?php 
      $query = "SELECT create_dat, title, content FROM article";
      $result = mysqli_query($conn, $query);
      ?>
      <?php while($article = mysqli_fetch_assoc($result)) { ?>
        <div class="relative">
          <a href="#_" class="block overflow-hidden group rounded-xl shadow-lg">
            <img src="images/blog-cover.jpg" class="object-cover w-full h-56 transition-all duration-300 ease-out sm:h-64 group-hover:scale-110" alt="Adventure">
          </a>
          <div class="relative mt-5">
            <p class="uppercase font-semibold text-xs mb-2.5 text-purple-600"><?php echo date('F jS Y', strtotime($article['create_dat'])); ?></p>
            <a href="#" class="block mb-3 hover:underline">
              <h2 class="text-2xl font-bold leading-5 text-black dark:text-white transition-colors duration-200 hover:text-purple-700 dark:hover:text-purple-400">
                <?php echo htmlspecialchars($article['title']); ?>
              </h2>
            </a>
            <p class="mb-4 text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars($article['content']); ?></p>
            <a href="#_" class="font-medium underline text-purple-600 dark:text-purple-400">Read More</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
</body>
</html>