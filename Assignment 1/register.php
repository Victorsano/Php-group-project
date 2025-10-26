<?php
require_once 'config.php';
require_once 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!$username) $errors[] = 'Username is required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $password2) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)');
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $hash
            ]);

            // Auto-login user after registration
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = 'Username or email already exists.';
            } else {
                $errors[] = 'Registration failed: ' . $e->getMessage();
            }
        }
    }
}
?>

<h2>Create Your Account</h2>

<?php if ($errors): ?>
  <ul class="errors">
    <?php foreach ($errors as $err): ?>
      <li><?php echo htmlspecialchars($err); ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" action="register.php" class="auth-form">
  <label>Username</label>
  <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '') ?>" required>

  <label>Email</label>
  <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>" required>

  <label>Password</label>
  <input type="password" name="password" required>

  <label>Confirm Password</label>
  <input type="password" name="password2" required>

  <button type="submit">Register</button>

  <p class="small-text">Already have an account? <a href="login.php">Login here</a>.</p>
</form>

</main>
<?php require 'footer.php'; ?>
</body>
</html>
