<?php
// Для різних background-сторінок — встанови $page_class у потрібному файлі перед підключенням header.php
if (!isset($page_class)) $page_class = '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>PowerFit — Фітнес Клуб</title>
    <link rel="stylesheet" href="../css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="<?=htmlspecialchars($page_class)?>">
<header>
    <div class="container header-flex">
        <div class="logo"><a href="/index.php">PowerFit</a></div>
        <nav>
            <a href="index.php">Головна</a>
            <a href="schedule.php">Розклад</a>
            <a href="trainers.php">Тренери</a>
            <a href="plans.php">Абонементи</a>
            <a href="contacts.php">Контакти</a>
            <a href="club_feedback.php">Відгуки</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Кабінет</a>
                <a href="logout.php" class="logout">Вийти</a>
            <?php else: ?>
                <a href="login.php">Увійти</a>
                <a href="register.php">Зареєструватися</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
