<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');

// ДОДАВАННЯ
if (isset($_POST['add'])) {
    $member_id = $_POST['member_id'];
    $session_id = $_POST['session_id'];
    $booking_date = $_POST['booking_date'];
    $pdo->prepare("INSERT INTO bookings (member_id, session_id, booking_date) VALUES (?, ?, ?)")
        ->execute([$member_id, $session_id, $booking_date]);
    header("Location: bookings.php");
    exit;
}

// ВИДАЛЕННЯ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM bookings WHERE id=?")->execute([$_GET['del']]);
    header("Location: bookings.php");
    exit;
}

$members = $pdo->query("SELECT * FROM members")->fetchAll();
$sessions = $pdo->query(
    "SELECT s.id, s.session_date, c.name as class_name, t.full_name as trainer_name
     FROM sessions s
     JOIN classes c ON s.class_id = c.id
     JOIN trainers t ON s.trainer_id = t.id
     ORDER BY s.session_date ASC"
)->fetchAll();

$bookings = $pdo->query(
    "SELECT b.*, m.full_name as member_name, s.session_date, c.name as class_name, t.full_name as trainer_name
     FROM bookings b
     JOIN members m ON b.member_id = m.id
     JOIN sessions s ON b.session_id = s.id
     JOIN classes c ON s.class_id = c.id
     JOIN trainers t ON s.trainer_id = t.id
     ORDER BY b.booking_date DESC"
)->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h2>Бронювання</h2>
<table>
<tr>
    <th>Клієнт</th><th>Заняття</th><th>Тренер</th><th>Дата тренування</th><th>Дата бронювання</th><th></th>
</tr>
<?php foreach($bookings as $b): ?>
<tr>
    <td><?=htmlspecialchars($b['member_name'])?></td>
    <td><?=htmlspecialchars($b['class_name'])?></td>
    <td><?=htmlspecialchars($b['trainer_name'])?></td>
    <td><?=htmlspecialchars($b['session_date'])?></td>
    <td><?=htmlspecialchars($b['booking_date'])?></td>
    <td><a href="?del=<?=$b['id']?>" onclick="return confirmDelete('Видалити бронювання?')">❌</a></td>
</tr>
<?php endforeach; ?>
</table>
<h3>Додати бронювання</h3>
<form method="post">
    <select name="member_id" required>
        <option value="">Клієнт</option>
        <?php foreach($members as $m): ?>
            <option value="<?=$m['id']?>"><?=htmlspecialchars($m['full_name'])?></option>
        <?php endforeach; ?>
    </select>
    <select name="session_id" required>
        <option value="">Заняття</option>
        <?php foreach($sessions as $s): ?>
            <option value="<?=$s['id']?>"><?=date('d.m.Y H:i', strtotime($s['session_date']))." | ".$s['class_name']." | ".$s['trainer_name']?></option>
        <?php endforeach; ?>
    </select>
    <input type="datetime-local" name="booking_date" required>
    <button name="add" type="submit">Додати</button>
</form>
<?php include '../templates/footer.php'; ?>
