<?php
session_start();
require_once '../db.php';

// Перевірка авторизації
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Отримати дані користувача + абонемент
$stmt = $pdo->prepare("SELECT m.*, p.name AS plan_name, p.price, m.membership_expires
                       FROM members m
                       LEFT JOIN membership_plans p ON m.membership_plan_id = p.id
                       WHERE m.id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$error = '';
// Оновлення email/телефону
if (isset($_POST['update'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $stmt = $pdo->prepare("UPDATE members SET email=?, phone=? WHERE id=?");
    $stmt->execute([$email, $phone, $user_id]);
    header("Location: profile.php?updated=1");
    exit;
}
// Оновлення пароля
if (isset($_POST['update_pass'])) {
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
    if ($pass === $pass2 && strlen($pass) >= 6) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE members SET password=? WHERE id=?")->execute([$hash, $user_id]);
        header("Location: profile.php?pass_updated=1");
        exit;
    } else {
        $error = "Паролі не співпадають або надто короткі!";
    }
}
?>

<?php include 'templates/header.php'; ?>
<div class="container">
<h2>Особистий кабінет</h2>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert success">Дані оновлено!</div>
<?php endif; ?>
<?php if (isset($_GET['pass_updated'])): ?>
    <div class="alert success">Пароль змінено!</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="profile-block">
    <p><b>ПІБ:</b> <?=htmlspecialchars($user['full_name'])?></p>
    <p><b>Email:</b> <?=htmlspecialchars($user['email'])?></p>
    <p><b>Телефон:</b> <?=htmlspecialchars($user['phone'])?></p>
    <p><b>Абонемент:</b> <?=htmlspecialchars($user['plan_name'])?> (<?=number_format($user['price'], 2)?> ₴)</p>
    <p><b>Діє до:</b> <?=$user['membership_expires'] ? htmlspecialchars(date('d.m.Y', strtotime($user['membership_expires']))) : '—' ?></p>
</div>

<h3>Оновити дані</h3>
<form method="post" class="profile-form">
    <input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required>
    <input type="text" name="phone" value="<?=htmlspecialchars($user['phone'])?>" placeholder="Телефон">
    <button type="submit" name="update">Зберегти</button>
</form>

<h3>Змінити пароль</h3>
<form method="post" class="profile-form">
    <input type="password" name="pass" placeholder="Новий пароль (мін. 6 символів)" required>
    <input type="password" name="pass2" placeholder="Повторіть пароль" required>
    <button type="submit" name="update_pass">Змінити</button>
</form>

<a href="logout.php" class="logout-btn">Вийти</a>
<hr>

<!-- Кнопка продовження абонемента -->
<form method="post" action="renew.php" style="margin-top: 20px;">
    <button type="submit" class="renew-btn">Продoвжити абонемент</button>
</form>

<!-- Історія занять -->
<?php include 'my_bookings.php'; ?>

</div>
<?php include 'templates/footer.php'; ?>
