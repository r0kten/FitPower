<?php
// Для різних background-сторінок — встанови $page_class у потрібному файлі перед підключенням header.php
if (!isset($page_class)) $page_class = '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>PowerFit — Фітнес Клуб</title>
    <link rel="stylesheet" href="../pages/css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Segoe UI альтернативи -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="<?=htmlspecialchars($page_class)?>">
<header class="pf-header">
    <div class="pf-logo">
        <a href="/index.php"><span>Power</span><span class="blue">Fit</span></a>
    </div>
    <nav class="pf-nav">
        <a href="index.php">Головна</a>
        <a href="schedule.php">Розклад</a>
        <a href="trainers.php">Тренери</a>
        <a href="plans.php">Абонементи</a>
        <a href="contacts.php">Контакти</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Кабінет</a>
            <a href="logout.php" class="btn-logout">Вийти</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Увійти</a>
        <?php endif; ?>
    </nav>
    <button class="burger" aria-label="Меню"><span></span><span></span><span></span></button>
</header>
<div class="pf-header-spacer"></div>
<script>
// --- Плавна поява хедера при завантаженні ---
window.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.pf-header').classList.add('show');
});

// --- Мобільне меню ---
document.querySelector('.burger').onclick = function() {
    document.querySelector('.pf-nav').classList.toggle('nav-open');
    this.classList.toggle('opened');
};
</script>
