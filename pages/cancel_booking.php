<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['user_id'])) die('Доступ заборонено');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $id = intval($_POST['booking_id']);
    // Перевірка, що цей запис належить саме цьому юзеру!
    $q = $pdo->prepare("SELECT * FROM bookings WHERE id=? AND member_id=?");
    $q->execute([$id, $_SESSION['user_id']]);
    if ($q->fetch()) {
        $pdo->prepare("UPDATE bookings SET status='cancelled' WHERE id=?")->execute([$id]);
    }
}
header('Location: profile.php');
exit;
