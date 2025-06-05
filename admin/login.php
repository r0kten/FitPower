<?php
session_start();
require_once '../db.php';

$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Невірний логін або пароль!";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід для адміністратора</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h1>Вхід в адмін-панель</h1>
<?php if ($error): ?><p style="color:red;"><?=$error?></p><?php endif; ?>
<form method="post">
    <label>Логін: <input type="text" name="username" required></label><br>
    <label>Пароль: <input type="password" name="password" required></label><br>
    <button type="submit" name="login">Увійти</button>
</form>
</body>
</html>
