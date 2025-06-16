<footer class="site-footer">
  © 2025 PowerFit. Всі права захищені.
</footer>
</body>
</html>


<!-- МОДАЛЬНЕ ВІКНО ВХОДУ ДЛЯ АДМІНА -->
<div id="adminLoginModal" style="display:none; position:fixed; left:0;top:0;width:100vw;height:100vh;z-index:9999;background:rgba(0,0,0,0.33);">
  <div style="max-width:360px;margin:100px auto;background:#fff;border-radius:13px;padding:36px 28px 18px 28px;box-shadow:0 10px 32px #13204330;position:relative;animation:showUp .6s;">
    <h3 style="color:#153269">Вхід адміністратора</h3>
    <form method="post" action="../admin/login.php">
      <input type="text" name="login" placeholder="Логін" required style="width:100%;margin-bottom:10px;">
      <input type="password" name="password" placeholder="Пароль" required style="width:100%;margin-bottom:16px;">
      <button class="btn" type="submit">Увійти</button>
      <button type="button" onclick="hideAdminModal()" style="margin-left:18px;">Закрити</button>
    </form>
  </div>
</div>

<script>
function showAdminModal() {
    document.getElementById('adminLoginModal').style.display = 'block';
    // Автофокус на логін
    setTimeout(function(){
        let input = document.querySelector('#adminLoginModal input[name="login"]');
        if(input) input.focus();
    }, 120);
}
function hideAdminModal() {
    document.getElementById('adminLoginModal').style.display = 'none';
}

// Відкрити модальне вікно по Ctrl+Shift+Ї або Ctrl+Shift+]
document.addEventListener('keydown', function(e){
    // e.code === 'BracketRight' — це кнопка ] (або Ї на укр)
    if (e.ctrlKey && e.shiftKey && (e.code === 'BracketRight' || e.key === 'Ї' || e.key === ']')) {
        showAdminModal();
        e.preventDefault();
    }
});
</script>

