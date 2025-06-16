<?php
require_once '../db.php';
$page_class = 'plans';
include 'templates/header.php';
$plans = $pdo->query("SELECT * FROM membership_plans")->fetchAll();
?>
<main>
  <section class="plans-section">
    <h1 class="plans-title">Абонементи PowerFit</h1>
    <div class="plans-list">
      <?php foreach($plans as $p): ?>
        <div class="plan-card">
          <h2 class="plan-name"><?= htmlspecialchars($p['name']) ?></h2>
          <div class="plan-duration">Тривалість: <b><?= (int)$p['duration_months'] ?> міс.</b></div>
          <div class="plan-price"><?= number_format($p['price'], 2) ?> <span>грн</span></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php include 'templates/footer.php'; ?>
