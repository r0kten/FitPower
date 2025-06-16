<?php
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  if (!isset($page_class)) $page_class = '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>FitPower</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/main.css">
</head>
<body class="<?= htmlspecialchars($page_class) ?>">
<header class="site-header">
  <div class="header-inner container">
    <div class="logo">Fit<span class="logo-accent">Power</span></div>
    <nav class="site-nav" id="nav">
      <a href="index.php" class="nav-link<?= $page_class=='home-page'?' active':'' ?>">Головна</a>
      <a href="schedule.php" class="nav-link<?= $page_class=='schedule'?' active':'' ?>">Розклад</a>
      <a href="trainers.php" class="nav-link<?= $page_class=='trainers'?' active':'' ?>">Тренери</a>
      <a href="plans.php" class="nav-link<?= $page_class=='plans'?' active':'' ?>">Абонементи</a>
      <a href="contacts.php" class="nav-link<?= $page_class=='contacts'?' active':'' ?>">Контакти</a>
      <a href="club_feedback.php" class="nav-link<?= $page_class=='feedback'?' active':'' ?>">Відгуки</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php" class="nav-link<?= $page_class=='profile'?' active':'' ?>">Профіль</a>
        <a href="logout.php" class="nav-link">Вийти</a>
      <?php else: ?>
        <a href="login.php" class="nav-link<?= $page_class=='login'?' active':'' ?>">Увійти</a>
        <a href="register.php" class="nav-link<?= $page_class=='register'?' active':'' ?>">Зареєструватися</a>
      <?php endif; ?>
    </nav>
    <button class="nav-toggle" id="navToggle" aria-label="Відкрити меню">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
<script>
  document.getElementById('navToggle').onclick = function() {
    document.getElementById('nav').classList.toggle('open');
  };
</script>
