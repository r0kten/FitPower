</main>
<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> PowerFit. Всі права захищені.</p>
    </div>
</footer>
</body>
</html>
<?php if (isset($_SESSION['admin_id'])): ?>
  <div style="position:fixed;bottom:28px;right:32px;z-index:10000;">
    <a href="../admin/" class="btn" style="font-size:1.05rem;padding:7px 22px;margin-bottom:6px;">Адмін-панель</a><br>
    <a href="../pages" class="btn" style="font-size:1.01rem;padding:6px 20px;">На сайт</a>
  </div>
<?php endif; ?>
