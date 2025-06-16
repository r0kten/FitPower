<?php
session_start();
require_once '../db.php';

/// Отримати список тренерів
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();

foreach ($trainers as $trainer) {
    // Вивід тренера
    echo "<div class='trainer-card'>";
    echo "<h3>".htmlspecialchars($trainer['full_name'])."</h3>";

    // Рейтинг тренера
    $q = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM trainer_ratings WHERE trainer_id=?");
    $q->execute([$trainer['id']]);
    $data = $q->fetch();
    $avg = $data['avg_rating'] ? round($data['avg_rating'], 1) : "—";
    echo "<div class='trainer-rating'><b>Рейтинг:</b> $avg ★ / 5 <span style='color:#999'>({$data['total']})</span></div>";

    // Коротко опис/спеціалізація
    if ($trainer['specialization']) {
        echo "<div class='trainer-spec'>".htmlspecialchars($trainer['specialization'])."</div>";
    }

    // Відгуки цього тренера
    $reviews = $pdo->prepare("SELECT r.*, m.full_name FROM trainer_ratings r JOIN members m ON r.member_id=m.id WHERE r.trainer_id=? ORDER BY r.created_at DESC");
    $reviews->execute([$trainer['id']]);
    echo "<div class='trainer-reviews'>";
    foreach($reviews as $rev) {
        echo "<div class='review'><b>".htmlspecialchars($rev['full_name'])."</b>: ";
        echo str_repeat('★', $rev['rating'])." ";
        if ($rev['comment']) echo "<i>".htmlspecialchars($rev['comment'])."</i>";
        echo "<span class='review-date'>".date('d.m.Y', strtotime($rev['created_at']))."</span></div>";
    }
    echo "</div>";

    // Форма додати відгук (тільки для залогіненого користувача, 1 раз на тренера)
    if (isset($_SESSION['user_id'])) {
        // Перевіряємо чи вже є відгук
        $already = $pdo->prepare("SELECT id FROM trainer_ratings WHERE member_id=? AND trainer_id=?");
        $already->execute([$_SESSION['user_id'], $trainer['id']]);
        if (!$already->fetch()) {
            ?>
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
                <button type="submit" class="btn">Залишити відгук</button>
            </form>
            <?php
        } else {
            echo "<div class='alert success'>Ви вже оцінили цього тренера</div>";
        }
    }
    echo "</div><hr>";
}
