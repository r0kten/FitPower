<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Адмін-панель PowerFit</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Адмін-панель</h2>
    <nav>
        <a href="members.php">Клієнти</a> |
        <a href="trainers.php">Тренери</a> |
        <a href="classes.php">Групові заняття</a> |
        <a href="sessions.php">Розклад</a> |
        <a href="bookings.php">Бронювання</a> |
        <a href="logout.php" style="float:right;">Вийти</a>
    </nav>
</div>
</body>
</html>
