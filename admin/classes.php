<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
// Додавання
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $capacity = $_POST['capacity'];
    $pdo->prepare("INSERT INTO classes (name, capacity) VALUES (?, ?)")
        ->execute([$name, $capacity]);
    header("Location: classes.php");
    exit;
}

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM classes WHERE id=?")->execute([$_GET['del']]);
    header("Location: classes.php");
    exit;
}

$classes = $pdo->query("SELECT * FROM classes")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Групові заняття</h2>
<table>
<tr><th>Назва</th><th>К-сть місць</th><th></th></tr>
<?php foreach($classes as $c): ?>
<tr>
    <td><?=htmlspecialchars($c['name'])?></td>
    <td><?=htmlspecialchars($c['capacity'])?></td>
    <td><a href="?del=<?=$c['id']?>" onclick="return confirm('Видалити заняття?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати заняття</h3>
<form method="post">
    <input type="text" name="name" placeholder="Назва" required>
    <input type="number" name="capacity" placeholder="Кількість місць" min="1" required>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
