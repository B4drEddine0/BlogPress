<?php
include('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];
    $article_id = $_POST['article_id'];
    
    // First check if visitor exists
    $check_visitor = "SELECT Visit_id FROM Visitor WHERE email = ?";
    $stmt = $conn->prepare($check_visitor);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Visitor exists, get their ID
        $visitor = $result->fetch_assoc();
        $visitor_id = $visitor['Visit_id'];
    } else {
        // Create new visitor
        $insert_visitor = "INSERT INTO Visitor (name_visit, email) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_visitor);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $visitor_id = $conn->insert_id;
    }
    
    // Add the comment
    $insert_comment = "INSERT INTO Comment (Visit_id, id_art, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_comment);
    $stmt->bind_param("iis", $visitor_id, $article_id, $content);
    
    if ($stmt->execute()) {
        header("Location: details.php?id=" . $article_id . "#comments");
    } else {
        echo "Error posting comment";
    }
}
?> 