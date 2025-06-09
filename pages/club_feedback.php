<?php
session_start();
require_once '../db.php';

// Додавання нового відгуку
if (isset($_POST['rate']) && isset($_SESSION['user_id'])) {
    $member_id = $_SESSION['user_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    // Захист: лише 1 відгук на місяць від одного користувача (приклад)
    $already = $pdo->prepare("SELECT COUNT(*) FROM club_feedback WHERE member_id=? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $already->execute([$member_id]);
    if ($already->fetchColumn() == 0 && $rating >= 1 && $rating <= 5 && $comment) {
        $pdo->prepare("INSERT INTO club_feedback (member_id, rating, comment) VALUES (?, ?, ?)")
            ->execute([$member_id, $rating, $comment]);
        header("Location: club_feedback.php?done=1");
        exit;
    } else {
        $error = "Ви вже залишали відгук нещодавно або некоректні дані.";
    }
}

// Виведення відгуків
$reviews = $pdo->query("SELECT cf.*, m.full_name FROM club_feedback cf JOIN members m ON cf.member_id = m.id ORDER BY cf.created_at DESC LIMIT 20")->fetchAll();
$avg = $pdo->query("SELECT AVG(rating) FROM club_feedback")->fetchColumn();
?>

<?php include 'templates/header.php'; ?>
<div class="container">
    <h2>Відгуки про PowerFit <?php if ($avg): ?>
        <span style="color:#f5a623;"><?=round($avg,1)?> ★</span>
    <?php endif; ?></h2>

    <?php if (isset($_GET['done'])): ?>
        <div class="alert success">Відгук додано! Дякуємо за вашу думку 💙</div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
    <form method="post" style="margin-bottom: 28px;">
        <label>Оцінка:
            <select name="rating" required>
                <option value="">—</option>
                <option value="5">5 ★</option>
                <option value="4">4 ★</option>
                <option value="3">3 ★</option>
                <option value="2">2 ★</option>
                <option value="1">1 ★</option>
            </select>
        </label>
        <textarea name="comment" rows="2" maxlength="255" placeholder="Ваш відгук (до 255 символів)..." required></textarea>
        <button name="rate" class="btn" type="submit">Надіслати відгук</button>
    </form>
    <?php else: ?>
        <div>Щоб залишити відгук, <a href="login.php">увійдіть у профіль</a>.</div>
    <?php endif; ?>

    <h3>Останні відгуки</h3>
    <?php if ($reviews): ?>
        <ul style="list-style:none;padding-left:0;">
            <?php foreach ($reviews as $r): ?>
                <li style="margin-bottom: 18px; border-bottom:1px solid #eef; padding-bottom:14px;">
                    <b><?=htmlspecialchars($r['full_name'])?></b>
                    <span style="color:#f5a623;font-size:1.2em;"><?=str_repeat("★",(int)$r['rating'])?></span>
                    <br>
                    <small><?=date('d.m.Y', strtotime($r['created_at']))?></small>
                    <div><?=htmlspecialchars($r['comment'])?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div>Поки що відгуків немає — станьте першим!</div>
    <?php endif; ?>
</div>
<?php include 'templates/footer.php'; ?>
