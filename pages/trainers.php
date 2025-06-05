<?php
require_once '../db.php';
require_once '../templates/header.php';

$stmt = $pdo->query("SELECT * FROM trainers");
$trainers = $stmt->fetchAll();
?>
<h2>Наші тренери</h2>
<table>
    <tr><th>ПІБ</th><th>Спеціалізація</th></tr>
    <?php foreach ($trainers as $tr): ?>
        <tr>
            <td><?= htmlspecialchars($tr['full_name']) ?></td>
            <td><?= htmlspecialchars($tr['specialization']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../templates/footer.php'; ?>
