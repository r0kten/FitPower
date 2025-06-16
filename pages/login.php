<?php
session_start();
require_once '../db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM members WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Невірний логін або пароль!';
    }
}
include 'templates/header.php';
?>
<section class="auth-section">
  <div class="auth-box">
    <h2>Вхід</h2>
    <?php if($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button type="submit">Увійти</button>
    </form>
    <a href="register.php" class="auth-link">Ще не маєте акаунту? Зареєструватись</a>
  </div>
</section>
<?php include 'templates/footer.php'; ?>
