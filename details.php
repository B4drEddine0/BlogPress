<?php 
session_start();
include ('connexion.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$query = "SELECT a.*, au.name_author 
          FROM article a 
          JOIN author au ON a.Author_id = au.Author_id 
          WHERE a.id_art = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    header('Location: index.php');
    exit;
}

$update_views = "UPDATE article SET views = views + 1 WHERE id_art = ?";
$stmt = $conn->prepare($update_views);
$stmt->bind_param("i", $id);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($article['title']); ?></title>
</head>
<body class="flex flex-col min-h-screen">
    <?php include('header.php'); ?>

    <main class="flex-grow">
        <div class="relative p-4">
            <div class="max-w-3xl mx-auto">
                <div class="mt-3 bg-white rounded-b lg:rounded-b-none lg:rounded-r flex flex-col justify-between leading-normal">
                    <div class="">
                        <h1 class="text-gray-900 font-bold text-4xl mb-4"><?php echo htmlspecialchars($article['title']); ?></h1>
                        
                        <div class="py-5 text-sm font-regular text-gray-900 flex">
                            <span class="mr-3 flex flex-row items-center">
                               <svg class="text-indigo-600" fill="currentColor" height="13px" width="13px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z"></path></g></g></svg>
                              <span class="ml-1"><?php echo date('F jS Y', strtotime($article['create_dat'])); ?></span>
                            </span>

                            <span class="flex flex-row items-center hover:text-indigo-600 mr-3">
                                <svg class="text-indigo-600" fill="currentColor" height="16px" aria-hidden="true" role="img"
                                    focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                    </path>
                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                </svg>
                                <span class="ml-1"><?php echo htmlspecialchars($article['name_author']); ?></span>
                            </span>

                            <span class="flex flex-row items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span><?php echo $article['views']; ?> views</span>
                            </span>
                        </div>
                        <hr>
                        <p class="text-base leading-8 my-5">
                            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                        </p>

                        <div class="mt-4">
                            <a href="index.php" class="text-indigo-600 hover:text-indigo-800">← Back to Articles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>