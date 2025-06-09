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
        $_SESSION['user_id'] = $user['id'];    // зберігаємо id користувача
        $_SESSION['user_email'] = $user['email'];
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Невірний логін або пароль!';
    }
}
?>
<?php include 'templates/header.php'; ?>
<h2>Вхід</h2>
<?php if($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Пароль" required><br>
    <button type="submit">Увійти</button>
</form>

<?php include 'templates/footer.php'; ?>
