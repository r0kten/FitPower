<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

// ДОДАТИ
if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $spec = $_POST['specialization'];
    $cert = $_POST['certification'];
    $desc = $_POST['description'];
    $photo = $_POST['photo']; // URL або відносний шлях
    $pdo->prepare("INSERT INTO trainers (full_name, specialization, certification, description, photo) VALUES (?, ?, ?, ?, ?)")
        ->execute([$full_name, $spec, $cert, $desc, $photo]);
    header("Location: trainers.php");
    exit;
}

// ВИДАЛИТИ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM trainers WHERE id=?")->execute([$_GET['del']]);
    header("Location: trainers.php");
    exit;
}

// ОНОВИТИ
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $spec = $_POST['specialization'];
    $cert = $_POST['certification'];
    $desc = $_POST['description'];
    $photo = $_POST['photo'];
    $pdo->prepare("UPDATE trainers SET full_name=?, specialization=?, certification=?, description=?, photo=? WHERE id=?")
        ->execute([$full_name, $spec, $cert, $desc, $photo, $id]);
    header("Location: trainers.php");
    exit;
}

$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Тренери</h2>
<table>
<tr><th>Фото</th><th>ПІБ</th><th>Спеціалізація</th><th>Сертифікати</th><th>Опис</th><th></th></tr>
<?php foreach($trainers as $t): ?>
<tr>
    <td>
      <img src="<?=htmlspecialchars($t['photo'] ?: '/assets/img/default_trainer.jpg')?>" width="60" height="60" style="object-fit:cover;border-radius:6px;">
    </td>
    <td><?=htmlspecialchars($t['full_name'])?></td>
    <td><?=htmlspecialchars($t['specialization'])?></td>
    <td><?=htmlspecialchars($t['certification'])?></td>
    <td><?=htmlspecialchars($t['description'])?></td>
    <td>
      <form method="post" style="display:inline">
        <input type="hidden" name="id" value="<?=$t['id']?>">
        <input type="text" name="full_name" value="<?=htmlspecialchars($t['full_name'])?>" required>
        <input type="text" name="specialization" value="<?=htmlspecialchars($t['specialization'])?>">
        <input type="text" name="certification" value="<?=htmlspecialchars($t['certification'])?>">
        <input type="text" name="description" value="<?=htmlspecialchars($t['description'])?>">
        <input type="text" name="photo" value="<?=htmlspecialchars($t['photo'])?>">
        <button type="submit" name="edit">Зберегти</button>
      </form>
      <a href="?del=<?=$t['id']?>" onclick="return confirm('Видалити?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати тренера</h3>
<form method="post">
    <input type="text" name="full_name" placeholder="ПІБ" required>
    <input type="text" name="specialization" placeholder="Спеціалізація">
    <input type="text" name="certification" placeholder="Сертифікати">
    <input type="text" name="description" placeholder="Опис">
    <input type="text" name="photo" placeholder="URL фото">
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
