<?php
require_once 'config.php';
require_once 'header.php';

// Fetch posts with author info
$stmt = $pdo->query('
  SELECT posts.*, users.username 
  FROM posts 
  JOIN users ON posts.user_id = users.id 
  ORDER BY posts.created_at DESC
');
$posts = $stmt->fetchAll();
?>

<h2>Recent Posts</h2>

<?php foreach ($posts as $post): ?>
  <article class="post">
  <h3><?php echo htmlspecialchars($post['title']); ?></h3>
  <p class="meta">
    By <?php echo htmlspecialchars($post['username']); ?> — <?php echo $post['created_at']; ?>
  </p>
  <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>

  <?php if (is_logged_in() && $post['user_id'] == current_user_id()): ?>
    <div class="post-actions">
      <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> |
      <form method="POST" action="delete_post.php" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <button type="submit" onclick="return confirm('Delete this post?');">Delete</button>
      </form>
    </div>
  <?php endif; ?>
</article>
<?php endforeach; ?>
<br>
<br>
</main>
<?php require 'footer.php'; ?>
</body>
</html>
