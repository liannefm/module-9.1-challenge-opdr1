<?php
require_once 'config.php';

// Controleert of opgegeven e-mailadres geldig is
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

//slaat nieuwe comment veilig op in db
function save_comment($pdo, $name, $email, $comment) {
    $name = htmlspecialchars($name);
    $comment = htmlspecialchars($comment);

    $stmt = $pdo->prepare("INSERT INTO comments (name, email, comment) VALUES (:name, :email, :comment)");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':comment' => $comment
    ]);
}

//haalt alle comments op uit de db, nieuwste eerst
function get_comments($pdo) {
    $stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
