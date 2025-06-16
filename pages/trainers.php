<?php
session_start();
require_once '../db.php';

$page_class = 'trainers';
include 'templates/header.php';

// Отримати список тренерів
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
?>

<main>
  <section class="trainers-section">
    <h1 class="trainers-title">Наші тренери</h1>
    <div class="trainers-list">
      <?php foreach ($trainers as $trainer): ?>
        <div class="trainer-card">
          <div class="trainer-card-header">
            <div class="trainer-avatar">
              <!-- Аватар тренера або ініціали, якщо нема фото -->
              <?php if (!empty($trainer['photo_url'])): ?>
                <img src="<?=htmlspecialchars($trainer['photo_url'])?>" alt="Аватар">
              <?php else: ?>
                <span><?=mb_substr($trainer['full_name'], 0, 1)?></span>
              <?php endif; ?>
            </div>
            <div>
              <div class="trainer-name"><?=htmlspecialchars($trainer['full_name'])?></div>
              <?php
                // Рейтинг тренера
                $q = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM trainer_ratings WHERE trainer_id=?");
                $q->execute([$trainer['id']]);
                $data = $q->fetch();
                $avg = $data['avg_rating'] ? round($data['avg_rating'], 1) : "—";
              ?>
             <div class="trainer-rating" title="Рейтинг тренера">
    <span class="rating-num"><?=$avg?></span>
    <span class="stars">
      <?php
      if (is_numeric($avg)) {
        $fullStars = floor($avg);
        for ($i=1; $i<=5; $i++) {
          echo ($i <= $fullStars) ? "★" : "☆";
        }
      } else {
        echo str_repeat("☆", 5);
      }
      ?>
    </span>
    <span class="rating-count">(<?=$data['total']?>)</span>
</div>

              <?php if ($trainer['specialization']): ?>
                <div class="trainer-spec"><?=htmlspecialchars($trainer['specialization'])?></div>
              <?php endif; ?>
            </div>
          </div>
          <!-- Відгуки -->
          <div class="trainer-reviews">
            <?php
              $reviews = $pdo->prepare("SELECT r.*, m.full_name FROM trainer_ratings r JOIN members m ON r.member_id=m.id WHERE r.trainer_id=? ORDER BY r.created_at DESC");
              $reviews->execute([$trainer['id']]);
            ?>
            <?php foreach($reviews as $rev): ?>
              <div class="review">
                <b><?=htmlspecialchars($rev['full_name'])?></b>
                <span class="review-stars"><?=str_repeat('★', $rev['rating'])?></span>
                <?php if ($rev['comment']): ?>
                  <span class="review-text"><?=htmlspecialchars($rev['comment'])?></span>
                <?php endif; ?>
                <span class="review-date"><?=date('d.m.Y', strtotime($rev['created_at']))?></span>
              </div>
            <?php endforeach; ?>
          </div>
          <!-- Додати відгук -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <?php
              // Перевіряємо чи вже є відгук
              $already = $pdo->prepare("SELECT id FROM trainer_ratings WHERE member_id=? AND trainer_id=?");
              $already->execute([$_SESSION['user_id'], $trainer['id']]);
            ?>
            <?php if (!$already->fetch()): ?>
              <form method="post" action="rate_trainer.php" class="rate-form">
                <input type="hidden" name="trainer_id" value="<?=$trainer['id']?>">
                <label>Оцінка:
                  <select name="rating" required>
                    <option value="">★</option>
                    <?php for($i=5;$i>=1;$i--): ?>
                      <option value="<?=$i?>"><?=$i?></option>
                    <?php endfor; ?>
                  </select>
                </label>
                <input type="text" name="comment" maxlength="255" placeholder="Ваш коментар">
                <button type="submit" class="btn btn-primary btn-small">Залишити відгук</button>
              </form>
            <?php else: ?>
              <div class="alert success">Ви вже оцінили цього тренера</div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php include 'templates/footer.php'; ?>
