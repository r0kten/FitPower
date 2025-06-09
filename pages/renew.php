<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;

// Проста заявка адміну (у вас може бути email-нотифікація, зараз просто в таблицю)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("INSERT INTO renew_requests (member_id, request_date) VALUES (?, NOW())")->execute([$user_id]);
    $success = true;
}

include 'templates/header.php';
?>

<h2>Заявка на продовження абонемента</h2>
<?php if ($success): ?>
    <div class="alert success">Ваша заявка на продовження абонемента відправлена! Адміністратор зв'яжеться з вами.</div>
<?php else: ?>
    <form method="post">
        <button type="submit">Відправити заявку</button>
    </form>
<?php endif; ?>

<a href="profile.php">Повернутись до кабінету</a>
<?php include 'templates/footer.php'; ?>
