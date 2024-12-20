<?php
session_start();
include('connexion.php');

if(isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];
    
    // Get current likes count
    $get_likes = "SELECT likes FROM article WHERE id_art = ?";
    $stmt = $conn->prepare($get_likes);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current = $result->fetch_assoc();
    
    // Check if user has already liked this article using session
    $liked_articles = isset($_SESSION['liked_articles']) ? $_SESSION['liked_articles'] : array();
    
    if (!in_array($article_id, $liked_articles)) {
        // User hasn't liked this article yet
        $new_likes = $current['likes'] + 1;
        $action = 'liked';
        $liked_articles[] = $article_id;
    } else {
        // User has already liked this article
        $new_likes = $current['likes'] - 1;
        $action = 'unliked';
        $liked_articles = array_diff($liked_articles, [$article_id]);
    }
    
    // Update likes count
    $update_query = "UPDATE article SET likes = ? WHERE id_art = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $new_likes, $article_id);
    
    if($stmt->execute()) {
        $_SESSION['liked_articles'] = $liked_articles;
        echo json_encode(['status' => 'success', 'action' => $action]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?> 