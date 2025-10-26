<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini PHP Blog group 18</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <h1><a href="index.php">Mini PHP Blog</a></h1>
    <nav>
      <a href="index.php">Home</a>
      <?php if (is_logged_in()): ?>
        <a href="create_post.php">New Post</a>
        <span>Hello, <?php echo htmlspecialchars(current_username()); ?></span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">