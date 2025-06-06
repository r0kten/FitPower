<?php
require_once '../db.php';
include 'templates/header.php';
$sessions = $pdo->query("
    SELECT s.*, c.name AS class_name, t.full_name AS trainer_name 
    FROM sessions s
    LEFT JOIN classes c ON s.class_id = c.id
    LEFT JOIN trainers t ON s.trainer_id = t.id
    ORDER BY s.session_date ASC
")->fetchAll();
?>
<div class="container">
    <h2>Розклад групових занять</h2>
    <table class="schedule-table">
        <tr>
            <th>Дата</th>
            <th>Час</th>
            <th>Тривалість</th>
            <th>Група</th>
            <th>Тренер</th>
        </tr>
        <?php foreach($sessions as $s): ?>
            <tr>
                <td><?= date('d.m.Y', strtotime($s['session_date'])) ?></td>
                <td><?= date('H:i', strtotime($s['session_date'])) ?></td>
                <td><?= (int)$s['duration'] ?> хв</td>
                <td><?= htmlspecialchars($s['class_name']) ?></td>
                <td><?= htmlspecialchars($s['trainer_name']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php include 'templates/footer.php'; ?>
