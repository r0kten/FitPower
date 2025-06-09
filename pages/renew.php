<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];
// Чи вже є активна заявка
$q = $pdo->prepare("SELECT id FROM renew_requests WHERE member_id=? AND status='pending'");
$q->execute([$user_id]);
if ($q->fetch()) {
    header("Location: profile.php?msg=Ваша заявка вже створена!");
    exit;
}
$pdo->prepare("INSERT INTO renew_requests (member_id, request_date) VALUES (?, NOW())")->execute([$user_id]);
header("Location: profile.php?msg=Заявка на продовження абонемента створена!");
exit;
