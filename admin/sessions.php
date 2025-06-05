<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');

// ДОДАВАННЯ
if (isset($_POST['add'])) {
    $class_id = $_POST['class_id'];
    $trainer_id = $_POST['trainer_id'];
    $session_date = $_POST['session_date'];
    $duration = $_POST['duration'];
    $pdo->prepare("INSERT INTO sessions (class_id, trainer_id, session_date, duration) VALUES (?, ?, ?, ?)")
        ->execute([$class_id, $trainer_id, $session_date, $duration]);
    header("Location: sessions.php");
    exit;
}

// ВИДАЛЕННЯ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM sessions WHERE id=?")->execute([$_GET['del']]);
    header("Location: sessions.php");
    exit;
}

$classes = $pdo->query("SELECT * FROM classes")->fetchAll();
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
$sessions = $pdo->query(
    "SELECT s.*, c.name as class_name, t.full_name as trainer_name
     FROM sessions s
     JOIN classes c ON s.class_id = c.id
     JOIN trainers t ON s.trainer_id = t.id
     ORDER BY s.session_date ASC"
)->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h2>Розклад (Sessions)</h2>
<table>
<tr>
    <th>Дата</th><th>Тривалість</th><th>Заняття</th><th>Тренер</th><th></th>
</tr>
<?php foreach($sessions as $s): ?>
<tr>
    <td><?=htmlspecialchars($s['session_date'])?></td>
    <td><?=htmlspecialchars($s['duration'])?> хв</td>
    <td><?=htmlspecialchars($s['class_name'])?></td>
    <td><?=htmlspecialchars($s['trainer_name'])?></td>
    <td><a href="?del=<?=$s['id']?>" onclick="return confirmDelete('Видалити заняття?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати заняття в розклад</h3>
<form method="post">
    <select name="class_id" required>
        <option value="">Заняття</option>
        <?php foreach($classes as $cl): ?>
            <option value="<?=$cl['id']?>"><?=htmlspecialchars($cl['name'])?></option>
        <?php endforeach; ?>
    </select>
    <select name="trainer_id" required>
        <option value="">Тренер</option>
        <?php foreach($trainers as $tr): ?>
            <option value="<?=$tr['id']?>"><?=htmlspecialchars($tr['full_name'])?></option>
        <?php endforeach; ?>
    </select>
    <input type="datetime-local" name="session_date" required>
    <input type="number" name="duration" placeholder="Тривалість (хв)" required>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../templates/footer.php'; ?>
