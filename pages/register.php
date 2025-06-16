<?php
require_once '../db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $plan = $_POST['membership_plan_id'] ? (int)$_POST['membership_plan_id'] : null;
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];
    if ($pass !== $pass2) {
        $error = "Паролі не співпадають!";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Такий email вже зареєстровано!";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO members (full_name, email, phone, password, membership_plan_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $phone, $hash, $plan]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: profile.php');
            exit;
        }
    }
}

$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
include 'templates/header.php';
?>
<section class="auth-section">
  <div class="auth-box">
    <h2>Реєстрація</h2>
    <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="text" name="full_name" placeholder="ПІБ" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Телефон">
      <input type="password" name="password" placeholder="Пароль" required>
      <input type="password" name="password2" placeholder="Повторіть пароль" required>
      <select name="membership_plan_id" required>
        <option value="">Оберіть абонемент</option>
        <?php foreach($plans as $plan): ?>
            <option value="<?=$plan['id']?>"><?=$plan['name']?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Зареєструватися</button>
    </form>
    <a href="login.php" class="auth-link">Вже є акаунт? Вхід</a>
  </div>
</section>
<?php include 'templates/footer.php'; ?>
