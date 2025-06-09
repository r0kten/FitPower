<?php
require_once '../db.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: members.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE login = ?");
    $stmt->execute([$login]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_login'] = $admin['login'];
        header('Location: members.php');
        exit;
    } else {
        $error = 'Невірний логін або пароль!';
    }
}
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Вхід в адмін-панель</h2>
<?php if($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <input type="text" name="login" placeholder="Логін" required><br>
    <input type="password" name="password" placeholder="Пароль" required><br>
    <button type="submit">Увійти</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
