<?php
require_once '../db.php';
include 'templates/header.php';
$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
?>
<div class="container">
    <h2>Абонементи PowerFit</h2>
    <div class="plans-list">
        <?php foreach($plans as $p): ?>
            <div class="plan-card">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <div class="plan-duration">Тривалість: <?= (int)$p['duration_months'] ?> міс.</div>
                <div class="plan-price"><?= number_format($p['price'], 2) ?> грн</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
