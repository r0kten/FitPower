<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_id'])) {
    $sid = intval($_POST['session_id']);

    // Перевірка, чи ще є місця
    $row = $pdo->prepare("
      SELECT s.capacity, COUNT(b.id) as booked_count
      FROM sessions s
      LEFT JOIN bookings b ON b.session_id = s.id
      WHERE s.id=?
      GROUP BY s.id
    ");
    $row->execute([$sid]);
    $row = $row->fetch();

    // Перевірка чи користувач вже записаний
    $chk = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE session_id=? AND member_id=?");
    $chk->execute([$sid, $user_id]);
    $already = $chk->fetchColumn();

    if ($row && $row['booked_count'] < $row['capacity'] && !$already) {
        $stmt = $pdo->prepare("INSERT INTO bookings (member_id, session_id, booking_date) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $sid]);
        header("Location: schedule.php?success=1");
    } else {
        header("Location: schedule.php?error=1");
    }
    exit;
}
header("Location: schedule.php");
