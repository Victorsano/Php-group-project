<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

 // get post ID from query strings
$post_id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id AND user_id = :uid');
$stmt->execute([':id' => $post_id, ':uid' => current_user_id()]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found or you don't have permission to edit it.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $update = $pdo->prepare('UPDATE posts SET title = :title, content = :content, updated_at = NOW() WHERE id = :id AND user_id = :uid');
        $update->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $post_id,
            ':uid' => current_user_id()
        ]);
        header('Location: index.php');
        exit;
    } else {
        $error = 'All fields are required.';
    }
}

require_once 'header.php';
?>

 <h2>Edit Post</h2>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

    <form method="POST" action="" class="auth-form">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

    <label>Content</label>
    <textarea name="content" rows="8" required><?php echo htmlspecialchars($post['content']); ?></textarea>

    <button type="submit">Save Changes</button>
    </form>

    <?php require 'footer.php'; ?>
