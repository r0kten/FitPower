<?php
session_start();
require_once '../db.php';

// Витягуємо всіх тренерів з середнім рейтингом
$sql = "SELECT t.*, 
               COALESCE(AVG(r.rating),0) as avg_rating,
               COUNT(r.id) as reviews_count
        FROM trainers t
        LEFT JOIN trainer_ratings r ON t.id = r.trainer_id
        GROUP BY t.id
        ORDER BY avg_rating DESC";
$trainers = $pdo->query($sql)->fetchAll();
?>
<?php include 'templates/header.php'; ?>
<div class="container">
    <h2>Наші тренери</h2>
    <div class="trainers-list">
    <?php foreach ($trainers as $tr): ?>
      <div class="trainer-card">
        <img src="<?= htmlspecialchars($tr['photo'] ?: '/assets/img/default_trainer.jpg') ?>" alt="Фото тренера">
        <div class="trainer-card-body">
            <h3><?= htmlspecialchars($tr['full_name']) ?></h3>
            <div class="trainer-desc"><?= nl2br(htmlspecialchars($tr['description'])) ?></div>
            <div class="trainer-rating">
                <?php 
                  $avg = round($tr['avg_rating'], 2);
                  $stars = round($avg);
                  for ($i=1;$i<=5;$i++) echo $i<=$stars?'⭐':'☆';
                  echo " <span>($avg/5, {$tr['reviews_count']} відгуків)</span>";
                ?>
            </div>
            <a href="rate_trainer.php?id=<?= $tr['id'] ?>" class="btn">Оцінити/залишити відгук</a>
        </div>
        <!-- Відгуки -->
        <div class="trainer-reviews">
        <?php
        $stmt = $pdo->prepare("SELECT r.*, m.full_name FROM trainer_ratings r JOIN members m ON r.member_id=m.id WHERE trainer_id=? ORDER BY r.created_at DESC LIMIT 3");
        $stmt->execute([$tr['id']]);
        $reviews = $stmt->fetchAll();
        if ($reviews):
            echo "<h4>Останні відгуки:</h4>";
            foreach ($reviews as $rv):
        ?>
            <div class="review">
                <b><?= htmlspecialchars($rv['full_name']) ?>:</b>
                <span>
                <?php for ($i=1;$i<=5;$i++) echo $i<=$rv['rating']?'⭐':'☆'; ?>
                </span>
                <div><?= htmlspecialchars($rv['comment']) ?></div>
            </div>
        <?php endforeach; endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
