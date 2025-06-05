<?php
require_once '../db.php';
require_once '../templates/header.php';

$stmt = $pdo->query("SELECT s.*, c.name as class_name, t.full_name as trainer_name
    FROM sessions s
    JOIN classes c ON s.class_id = c.id
    JOIN trainers t ON s.trainer_id = t.id
    ORDER BY s.session_date ASC
");
$sessions = $stmt->fetchAll();
?>
<h2>Розклад занять</h2>
<table>
    <tr><th>Дата та час</th><th>Заняття</th><th>Тренер</th></tr>
    <?php foreach ($sessions as $s): ?>
        <tr>
            <td><?= date('d.m.Y H:i', strtotime($s['session_date'])) ?></td>
            <td><?= htmlspecialchars($s['class_name']) ?></td>
            <td><?= htmlspecialchars($s['trainer_name']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../templates/footer.php'; ?>
