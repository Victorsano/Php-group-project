<?php
require_once 'config.php';
require_once 'header.php';

$post_id = (int)($_GET['id'] ?? 0);

// Fetch post and author
$stmt = $pdo->prepare('
  SELECT posts.*, users.username 
  FROM posts 
  JOIN users ON posts.user_id = users.id 
  WHERE posts.id = :id
');
$stmt->execute([':id' => $post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found.</p>";
    require 'footer.php';
    exit;
}

// Handle new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $content = trim($_POST['content']);
    if ($content !== '') {
        $author_name = is_logged_in() ? current_username() : trim($_POST['author_name']);
        $user_id = is_logged_in() ? current_user_id() : null;
        $stmt = $pdo->prepare('
          INSERT INTO comments (post_id, user_id, author_name, content)
          VALUES (:post_id, :user_id, :author_name, :content)
        ');
        $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $user_id,
            ':author_name' => $author_name,
            ':content' => $content
        ]);
        header("Location: view_post.php?id=$post_id");
        exit;
    }
}

// Fetch comments
$cstmt = $pdo->prepare('
  SELECT c.*, u.username 
  FROM comments c 
  LEFT JOIN users u ON c.user_id = u.id 
  WHERE c.post_id = :pid 
  ORDER BY c.created_at DESC
');
$cstmt->execute([':pid' => $post_id]);
$comments = $cstmt->fetchAll();
?>

<article class="post">
  <h2><?php echo htmlspecialchars($post['title']); ?></h2>
  <p class="meta">By <?php echo htmlspecialchars($post['username']); ?> — <?php echo $post['created_at']; ?></p>
  <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
</article>

<section class="comments">
  <h3>Comments</h3>

  <?php if ($comments): ?>
    <?php foreach ($comments as $comment): ?>
      <div class="comment">
        <p><strong>
          <?php echo htmlspecialchars($comment['username'] ?? $comment['author_name']); ?>
        </strong> said:</p>
        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
        <p class="meta"><?php echo $comment['created_at']; ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No comments yet.</p>
  <?php endif; ?>

  <h4>Leave a Comment</h4>
  <form method="POST" action="view_post.php?id=<?php echo $post_id; ?>">
    <?php if (!is_logged_in()): ?>
      <input type="text" name="author_name" placeholder="Your name" required>
    <?php endif; ?>
    <textarea name="content" rows="4" placeholder="Write your comment..." required></textarea>
    <button type="submit" name="comment">Submit Comment</button>
  </form>
</section>

<?php require 'footer.php'; ?>
