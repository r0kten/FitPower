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
$page_class = 'profile';
include 'templates/header.php';
?>
<main>
  <section class="profile-section-row">
    <div class="profile-left">
      <div class="profile-block">
        <h1 class="profile-title">Особистий кабінет</h1>
        <?php if (isset($_GET['updated'])): ?>
          <div class="alert success">Дані оновлено!</div>
        <?php endif; ?>
        <?php if (isset($_GET['pass_updated'])): ?>
          <div class="alert success">Пароль змінено!</div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="profile-info">
          <div class="profile-avatar"><?=mb_substr($user['full_name'], 0, 1)?></div>
          <div>
            <div class="profile-name"><?=htmlspecialchars($user['full_name'])?></div>
            <div class="profile-field"><b>Email:</b> <?=htmlspecialchars($user['email'])?></div>
            <div class="profile-field"><b>Телефон:</b> <?=htmlspecialchars($user['phone'])?></div>
          </div>
        </div>
        <div class="profile-membership">
          <div><b>Абонемент:</b> <?=htmlspecialchars($user['plan_name'] ?: '—')?></div>
          <div><b>Вартість:</b> <?= $user['price'] ? number_format($user['price'], 2) . ' ₴' : '—' ?></div>
          <div><b>Діє до:</b> <?= $user['membership_expires'] ? htmlspecialchars(date('d.m.Y', strtotime($user['membership_expires']))) : '—' ?></div>
        </div>
        <form method="post" action="renew.php" class="renew-form">
          <button type="submit" class="btn btn-outline-primary">Продoвжити абонемент</button>
        </form>

        <h2 class="profile-subtitle">Оновити особисті дані</h2>
        <form method="post" class="profile-form">
          <input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required>
          <input type="text" name="phone" value="<?=htmlspecialchars($user['phone'])?>" placeholder="Телефон">
          <button type="submit" name="update" class="btn btn-primary">Зберегти</button>
        </form>

        <h2 class="profile-subtitle">Змінити пароль</h2>
        <form method="post" class="profile-form">
          <input type="password" name="pass" placeholder="Новий пароль (мін. 6 символів)" required>
          <input type="password" name="pass2" placeholder="Повторіть пароль" required>
          <button type="submit" name="update_pass" class="btn btn-primary">Змінити</button>
        </form>

        <a href="logout.php" class="logout-btn">Вийти з кабінету</a>
      </div>
    </div>
    <div class="profile-right">
      <div class="profile-history-block">
        <h2 class="profile-history-title">Історія відвідувань та записів</h2>
        <?php include 'my_bookings.php'; ?>
      </div>
    </div>
  </section>
</main>
<?php include 'templates/footer.php'; ?>
