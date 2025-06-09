<?php

require_once '../db.php';
$user_id = $_SESSION['user_id'] ?? 0;

// Скасування
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND member_id = ?");
    $stmt->execute([$_GET['cancel'], $user_id]);
    header("Location: profile.php?cancelled=1");
    exit;
}

// Витягуємо записи
$stmt = $pdo->prepare("
    SELECT b.*, s.session_date, s.duration, c.name as class_name, t.full_name as trainer
    FROM bookings b
    JOIN sessions s ON b.session_id = s.id
    JOIN classes c ON s.class_id = c.id
    JOIN trainers t ON s.trainer_id = t.id
    WHERE b.member_id = ?
    ORDER BY s.session_date DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<h3>Мої записи на тренування</h3>
<table>
<tr>
    <th>Дата</th><th>Час</th><th>Заняття</th><th>Тренер</th><th>Статус</th><th></th>
</tr>
<?php foreach ($bookings as $b): ?>
<tr>
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
        <?php if ($b['status'] == 'pending' && strtotime($b['session_date']) > time()): ?>
            <a href="?cancel=<?=$b['id']?>" onclick="return confirm('Скасувати запис?')" style="color:red;">Скасувати</a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
