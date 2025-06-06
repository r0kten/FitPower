<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');

// Додавання
if (isset($_POST['add'])) {
    $member_id = $_POST['member_id'];
    $session_id = $_POST['session_id'];
    $pdo->prepare("INSERT INTO bookings (member_id, session_id, booking_date) VALUES (?, ?, NOW())")
        ->execute([$member_id, $session_id]);
    header("Location: bookings.php");
    exit;
}

// Видалення
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM bookings WHERE id=?")->execute([$_GET['del']]);
    header("Location: bookings.php");
    exit;
}

$members = $pdo->query("SELECT * FROM members")->fetchAll();
$sessions = $pdo->query("SELECT s.*, c.name as class_name FROM sessions s LEFT JOIN classes c ON s.class_id = c.id")->fetchAll();
$bookings = $pdo->query("SELECT b.*, m.full_name as member_name, c.name as class_name, s.session_date FROM bookings b LEFT JOIN members m ON b.member_id = m.id LEFT JOIN sessions s ON b.session_id = s.id LEFT JOIN classes c ON s.class_id = c.id")->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h2>Бронювання</h2>
<table>
<tr><th>Клієнт</th><th>Заняття</th><th>Дата заняття</th><th>Броньовано</th><th></th></tr>
<?php foreach($bookings as $b): ?>
<tr>
    <td><?=htmlspecialchars($b['member_name'])?></td>
    <td><?=htmlspecialchars($b['class_name'])?></td>
    <td><?=htmlspecialchars($b['session_date'])?></td>
    <td><?=htmlspecialchars($b['booking_date'])?></td>
    <td><a href="?del=<?=$b['id']?>" onclick="return confirm('Видалити бронювання?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати бронювання</h3>
<form method="post">
    <select name="member_id" required>
        <option value="">Оберіть клієнта</option>
        <?php foreach($members as $m): ?>
            <option value="<?=$m['id']?>"><?=$m['full_name']?></option>
        <?php endforeach; ?>
    </select>
    <select name="session_id" required>
        <option value="">Оберіть сесію</option>
        <?php foreach($sessions as $s): ?>
            <option value="<?=$s['id']?>"><?=$s['class_name']?> (<?=date('d.m.Y H:i', strtotime($s['session_date']))?>)</option>
        <?php endforeach; ?>
    </select>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../templates/footer.php'; ?>
