<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');

// ДОДАВАННЯ
if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $spec = $_POST['specialization'];
    $cert = $_POST['certification'];
    $pdo->prepare("INSERT INTO trainers (full_name, specialization, certification) VALUES (?, ?, ?)")
        ->execute([$full_name, $spec, $cert]);
    header("Location: trainers.php");
    exit;
}

// ВИДАЛЕННЯ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM trainers WHERE id=?")->execute([$_GET['del']]);
    header("Location: trainers.php");
    exit;
}

// ВИТЯГ ВСІХ
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h2>Тренери</h2>
<table>
<tr><th>ПІБ</th><th>Спеціалізація</th><th>Сертифікати</th><th></th></tr>
<?php foreach($trainers as $tr): ?>
<tr>
    <td><?=htmlspecialchars($tr['full_name'])?></td>
    <td><?=htmlspecialchars($tr['specialization'])?></td>
    <td><?=htmlspecialchars($tr['certification'])?></td>
    <td>
        <a href="?del=<?=$tr['id']?>" onclick="return confirmDelete('Видалити тренера?')">❌</a>
    </td>
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
<?php include '../templates/footer.php'; ?>
