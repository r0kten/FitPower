<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Додавання
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

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM sessions WHERE id=?")->execute([$_GET['del']]);
    header("Location: sessions.php");
    exit;
}

$classes = $pdo->query("SELECT * FROM classes")->fetchAll();
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
$sessions = $pdo->query("SELECT s.*, c.name as class_name, t.full_name as trainer_name FROM sessions s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN trainers t ON s.trainer_id = t.id")->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Розклад (сесії)</h2>
<table>
<tr><th>Заняття</th><th>Тренер</th><th>Дата і час</th><th>Тривалість (хв)</th><th></th></tr>
<?php foreach($sessions as $s): ?>
<tr>
    <td><?=htmlspecialchars($s['class_name'])?></td>
    <td><?=htmlspecialchars($s['trainer_name'])?></td>
    <td><?=htmlspecialchars($s['session_date'])?></td>
    <td><?=htmlspecialchars($s['duration'])?></td>
    <td><a href="?del=<?=$s['id']?>" onclick="return confirm('Видалити сесію?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати сесію</h3>
<form method="post">
    <select name="class_id" required>
        <option value="">Оберіть заняття</option>
        <?php foreach($classes as $class): ?>
            <option value="<?=$class['id']?>"><?=$class['name']?></option>
        <?php endforeach; ?>
    </select>
    <select name="trainer_id" required>
        <option value="">Оберіть тренера</option>
        <?php foreach($trainers as $tr): ?>
            <option value="<?=$tr['id']?>"><?=$tr['full_name']?></option>
        <?php endforeach; ?>
    </select>
    <input type="datetime-local" name="session_date" required>
    <input type="number" name="duration" min="1" placeholder="Тривалість (хв)" required>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../admin/templates/footer.php'; ?>
