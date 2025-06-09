<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Додавання абонементу
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $duration = $_POST['duration_months'];
    $price = $_POST['price'];
    $pdo->prepare("INSERT INTO membership_plans (name, duration_months, price) VALUES (?, ?, ?)")
        ->execute([$name, $duration, $price]);
    header("Location: membership_plans.php");
    exit;
}

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM membership_plans WHERE id=?")->execute([$_GET['del']]);
    header("Location: membership_plans.php");
    exit;
}

$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Абонементи</h2>
<table>
<tr><th>Назва</th><th>Тривалість (міс)</th><th>Ціна</th><th></th></tr>
<?php foreach($plans as $plan): ?>
<tr>
    <td><?=htmlspecialchars($plan['name'])?></td>
    <td><?=htmlspecialchars($plan['duration_months'])?></td>
    <td><?=htmlspecialchars($plan['price'])?> грн</td>
    <td><a href="?del=<?=$plan['id']?>" onclick="return confirm('Видалити абонемент?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати абонемент</h3>
<form method="post">
    <input type="text" name="name" placeholder="Назва" required>
    <input type="number" name="duration_months" placeholder="Тривалість (міс)" min="1" required>
    <input type="number" name="price" step="0.01" placeholder="Ціна" min="0" required>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
