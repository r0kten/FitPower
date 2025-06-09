<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

// Підтвердити
if (isset($_GET['confirm']) && is_numeric($_GET['confirm'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$_GET['confirm']]);
    header("Location: bookings.php?confirmed=1");
    exit;
}
// Скасувати
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$_GET['cancel']]);
    header("Location: bookings.php?cancelled=1");
    exit;
}

// Витягуємо всі бронювання
$stmt = $pdo->query("
    SELECT b.*, m.full_name, s.session_date, c.name as class_name, t.full_name as trainer
    FROM bookings b
    JOIN members m ON b.member_id = m.id
    JOIN sessions s ON b.session_id = s.id
    JOIN classes c ON s.class_id = c.id
    JOIN trainers t ON s.trainer_id = t.id
    ORDER BY s.session_date DESC
");
$bookings = $stmt->fetchAll();
?>
<?php include '../admin/templates/header.php'; ?>
<h2>Бронювання</h2>
<table>
<tr>
    <th>Клієнт</th><th>Дата</th><th>Час</th><th>Заняття</th><th>Тренер</th><th>Статус</th><th></th>
</tr>
<?php foreach ($bookings as $b): ?>
<tr>
    <td><?=htmlspecialchars($b['full_name'])?></td>
    <td><?=date('d.m.Y', strtotime($b['session_date']))?></td>
    <td><?=date('H:i', strtotime($b['session_date']))?></td>
    <td><?=htmlspecialchars($b['class_name'])?></td>
    <td><?=htmlspecialchars($b['trainer'])?></td>
    <td>
        <?php
            if ($b['status'] == 'pending') echo '<span style="color:orange">Очікує</span>';
            elseif ($b['status'] == 'confirmed') echo '<span style="color:green">Підтверджено</span>';
            elseif ($b['status'] == 'cancelled') echo '<span style="color:red">Скасовано</span>';
        ?>
    </td>
    <td>
        <?php if ($b['status'] == 'pending'): ?>
            <a href="?confirm=<?=$b['id']?>" style="color:green;">Підтвердити</a> |
            <a href="?cancel=<?=$b['id']?>" style="color:red;" onclick="return confirm('Скасувати?')">Скасувати</a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php include '../admin/templates/footer.php'; ?>