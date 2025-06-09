<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Додавання
if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $specialization = $_POST['specialization'];
    $certification = $_POST['certification'];
    $pdo->prepare("INSERT INTO trainers (full_name, specialization, certification) VALUES (?, ?, ?)")
        ->execute([$full_name, $specialization, $certification]);
    header("Location: trainers.php");
    exit;
}

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM trainers WHERE id=?")->execute([$_GET['del']]);
    header("Location: trainers.php");
    exit;
}

$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Тренери</h2>
<table>
<tr><th>ПІБ</th><th>Спеціалізація</th><th>Сертифікат</th><th></th></tr>
<?php foreach($trainers as $t): ?>
<tr>
    <td><?=htmlspecialchars($t['full_name'])?></td>
    <td><?=htmlspecialchars($t['specialization'])?></td>
    <td><?=htmlspecialchars($t['certification'])?></td>
    <td><a href="?del=<?=$t['id']?>" onclick="return confirm('Видалити тренера?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати тренера</h3>
<form method="post">
    <input type="text" name="full_name" placeholder="ПІБ" required>
    <input type="text" name="specialization" placeholder="Спеціалізація">
    <input type="text" name="certification" placeholder="Сертифікат">
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
