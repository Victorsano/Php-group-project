<?php
require_once 'config.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    // verify post ownership
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id AND user_id = :uid');
    $stmt->execute([':id' => $id, ':uid' => current_user_id()]);
    $post = $stmt->fetch();

    if ($post) {
        $pdo->prepare('DELETE FROM posts WHERE id = :id')->execute([':id' => $id]);
    }
}
header('Location: index.php');
exit;
?>
