    <?php
    require_once 'config.php';
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if ($title && $content) {
            $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content) VALUES (:uid, :title, :content)');
            $stmt->execute([
                ':uid' => current_user_id(),
                ':title' => $title,
                ':content' => $content
            ]);
            header('Location: index.php');
            exit;
        } else {
            $error = 'All fields are required.';
        }
    }

    require_once 'header.php';
    ?>

<h2>Create New Post</h2>
<?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>

<form method="POST" action="">
  <label>Title</label><br>
  <input type="text" name="title" required><br>
  <label>Content</label><br>
  <textarea name="content" rows="8" required></textarea><br>
  <button type="submit">Publish</button>
</form>

</main>
<?php require 'footer.php'; ?>
</body>
</html>