<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Додавання
if (isset($_POST['add'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $plan = $_POST['membership_plan_id'] ? (int)$_POST['membership_plan_id'] : null;
    $pass = $_POST['password'];
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO members (full_name, email, phone, password, membership_plan_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $phone, $hash, $plan]);
    header("Location: members.php");
    exit;
}

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM members WHERE id=?")->execute([$_GET['del']]);
    header("Location: members.php");
    exit;
}

// Плани для вибору
$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
$members = $pdo->query("SELECT m.*, p.name as plan_name FROM members m LEFT JOIN membership_plans p ON m.membership_plan_id = p.id")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Клієнти</h2>
<table>
<tr><th>ПІБ</th><th>Email</th><th>Телефон</th><th>Абонемент</th><th></th></tr>
<?php foreach($members as $m): ?>
<tr>
    <td><?=htmlspecialchars($m['full_name'])?></td>
    <td><?=htmlspecialchars($m['email'])?></td>
    <td><?=htmlspecialchars($m['phone'])?></td>
    <td><?=htmlspecialchars($m['plan_name'])?></td>
    <td><a href="?del=<?=$m['id']?>" onclick="return confirm('Видалити клієнта?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати клієнта</h3>
<form method="post">
    <input type="text" name="full_name" placeholder="ПІБ" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Телефон">
    <input type="password" name="password" placeholder="Пароль" required>
    <select name="membership_plan_id" required>
        <option value="">Оберіть абонемент</option>
        <?php foreach($plans as $plan): ?>
            <option value="<?=$plan['id']?>"><?=$plan['name']?></option>
        <?php endforeach; ?>
    </select>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
