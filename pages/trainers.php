<?php
require_once '../db.php';
include 'templates/header.php';
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
?>
<div class="container">
    <h2>Наші тренери</h2>
    <div class="trainers-list">
        <?php foreach($trainers as $t): ?>
            <div class="trainer-card">
                <div class="trainer-photo">
                  <img src="assets/trainer.png" alt="<?= htmlspecialchars($t['full_name']) ?>">
                </div>
                <h3><?= htmlspecialchars($t['full_name']) ?></h3>
                <div class="special"><?= htmlspecialchars($t['specialization']) ?></div>
                <div class="cert"><?= htmlspecialchars($t['certification']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
