<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');

// ДОДАВАННЯ
if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $plan = $_POST['membership_plan_id'];
    $pdo->prepare("INSERT INTO members (full_name, email, phone, membership_plan_id) VALUES (?, ?, ?, ?)")
        ->execute([$full_name, $email, $phone, $plan]);
    header("Location: members.php");
    exit;
}

// ВИДАЛЕННЯ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM members WHERE id=?")->execute([$_GET['del']]);
    header("Location: members.php");
    exit;
}

// Плани для вибору
$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
$members = $pdo->query("SELECT m.*, p.name as plan_name FROM members m LEFT JOIN membership_plans p ON m.membership_plan_id = p.id")->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h2>Клієнти</h2>
<table>
<tr><th>ПІБ</th><th>Email</th><th>Телефон</th><th>Абонемент</th><th></th></tr>
<?php foreach($members as $m): ?>
<tr>
    <td><?=htmlspecialchars($m['full_name'])?></td>
    <td><?=htmlspecialchars($m['email'])?></td>
    <td><?=htmlspecialchars($m['phone'])?></td>
    <td><?=htmlspecialchars($m['plan_name'])?></td>
    <td><a href="?del=<?=$m['id']?>" onclick="return confirmDelete('Видалити клієнта?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати клієнта</h3>
<form method="post">
    <input type="text" name="full_name" placeholder="ПІБ" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Телефон">
    <select name="membership_plan_id" required>
        <option value="">Оберіть абонемент</option>
        <?php foreach($plans as $plan): ?>
            <option value="<?=$plan['id']?>"><?=$plan['name']?></option>
        <?php endforeach; ?>
    </select>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../templates/footer.php'; ?>
