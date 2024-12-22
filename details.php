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


if(isset($_POST['content'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];
    $article_id = $_POST['article_id'];
    
    $insert_visitor = "INSERT INTO Visitor (name_visit, email) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_visitor);
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $visitor_id = $conn->insert_id;
    
    $insert_comment = "INSERT INTO Comment (Visit_id, id_art, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_comment);
    $stmt->bind_param("iis", $visitor_id, $article_id, $content);
    $stmt->execute();
    
    header("Location: details.php?id=" . $article_id . "#comments");
    exit();
}

if(isset($_POST['toggle_like'])) {
    $article_id = $_POST['article_id'];
    $conn->query("UPDATE article SET likes = likes + 1 WHERE id_art = " . $article_id);
    header("Location: details.php?id=" . $article_id);
    exit();
}
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
                        <h1 class="text-gray-900 font-bold text-4xl mb-4"><? echo htmlspecialchars($article['title']); ?></h1>
                        
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

                            <span class="flex flex-row items-center ml-4">
                                <?php
                                $liked = isset($_SESSION['liked_articles']) && in_array($article['id_art'], $_SESSION['liked_articles']);
                                ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="article_id" value="<?php echo $article['id_art']; ?>">
                                    <button type="submit" name="toggle_like" class="flex items-center space-x-2 <?php echo $liked ? 'text-red-500' : 'text-gray-500'; ?> hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="<?php echo $liked ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span><?php echo $article['likes']; ?></span>
                                    </button>
                                </form>
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

    <div class="max-w-3xl mx-auto mt-8" id="comments">
        <h2 class="text-2xl font-bold mb-4">Comments</h2>
        
        <form method="POST" class="mb-8 bg-white p-6 rounded-lg shadow-md">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                <textarea name="content" id="content" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <input type="hidden" name="article_id" value="<?php echo $article['id_art']; ?>">
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Post Comment
            </button>
        </form>

        <div class="space-y-6">
            <?php
            $comments_query = "SELECT c.*, v.name_visit
                             FROM Comment c 
                             JOIN Visitor v ON c.Visit_id = v.Visit_id 
                             WHERE c.id_art = ? 
                             ORDER BY c.create_dat DESC";
            $stmt = $conn->prepare($comments_query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $comments = $stmt->get_result();
            
            while ($comment = $comments->fetch_assoc()):
            ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center">
                                <span class="text-indigo-800 font-semibold">
                                    <?php echo strtoupper(substr($comment['name_visit'], 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($comment['name_visit']); ?></h3>
                                <p class="text-sm text-gray-500">
                                    <?php echo date('F j, Y \a\t g:i a', strtotime($comment['create_dat'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>