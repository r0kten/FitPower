<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$trainer_id = (int)($_GET['id'] ?? 0);
$member_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = max(1, min(5, (int)$_POST['rating']));
    $comment = trim($_POST['comment']);
    // чи вже був відгук?
    $check = $pdo->prepare("SELECT id FROM trainer_ratings WHERE member_id=? AND trainer_id=?");
    $check->execute([$member_id, $trainer_id]);
    if ($check->fetch()) {
        $stmt = $pdo->prepare("UPDATE trainer_ratings SET rating=?, comment=?, created_at=NOW() WHERE member_id=? AND trainer_id=?");
        $stmt->execute([$rating, $comment, $member_id, $trainer_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO trainer_ratings (member_id, trainer_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$member_id, $trainer_id, $rating, $comment]);
    }
    header("Location: trainers.php?thanks=1");
    exit;
}
$stmt = $pdo->prepare("SELECT full_name FROM trainers WHERE id=?");
$stmt->execute([$trainer_id]);
$trainer = $stmt->fetch();
if (!$trainer) die("Тренер не знайдений");
?>
<?php include 'templates/header.php'; ?>
<div class="container">
<h2>Оцінка тренера: <?=htmlspecialchars($trainer['full_name'])?></h2>
<form method="post">
    <label>Оцінка (1-5):</label>
    <select name="rating" required>
        <?php for($i=5;$i>=1;$i--): ?>
          <option value="<?=$i?>"><?=$i?></option>
        <?php endfor; ?>
    </select><br>
    <label>Відгук (необов'язково):</label>
    <textarea name="comment" maxlength="255" rows="3"></textarea><br>
    <button type="submit" class="btn">Надіслати</button>
</form>
</div>
<?php include 'templates/footer.php'; ?>
