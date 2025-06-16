<footer class="site-footer">
  © 2025 PowerFit. Всі права захищені.
</footer>
</body>
</html>

<div id="adminLoginModal">
  <div class="admin-modal-inner">
    <h3>Вхід адміністратора</h3>
    <form method="post" action="../admin/login.php">
      <input type="text" name="login" placeholder="Логін" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button class="btn" type="submit">Увійти</button>
      <button type="button" class="btn-close" onclick="hideAdminModal()">Закрити</button>
    </form>
  </div>
</div>


<script>
function showAdminModal() {
    document.getElementById('adminLoginModal').style.display = 'block';
    setTimeout(function(){
        let input = document.querySelector('#adminLoginModal input[name="login"]');
        if(input) input.focus();
    }, 120);
}
function hideAdminModal() {
    document.getElementById('adminLoginModal').style.display = 'none';
}
document.addEventListener('keydown', function(e){
    if (e.ctrlKey && e.shiftKey && (e.code === 'BracketRight' || e.key === 'Ї' || e.key === ']')) {
        showAdminModal();
        e.preventDefault();
    }
});
</script>

<style>#adminLoginModal {
  display: none;
  position: fixed;
  left: 0; top: 0;
  width: 100vw; height: 100vh;
  z-index: 9999;
  background: rgba(25, 36, 64, 0.16);
  backdrop-filter: blur(1.7px);
}
.admin-modal-inner {
  max-width: 400px;
  margin: 90px auto 0 auto;
  background: #fff;
  border-radius: 18px;
  padding: 36px 28px 24px 28px;
  box-shadow: 0 12px 38px #1e377030, 0 2px 8px #47b1ff17;
  position: relative;
  animation: showUp .5s cubic-bezier(.43,1.6,.45,1.07);
}
@keyframes showUp {
  from { opacity: 0; transform: translateY(44px);}
  to   { opacity: 1; transform: none;}
}
.admin-modal-inner h3 {
  color: #153269;
  font-size: 1.29rem;
  font-weight: 800;
  margin-bottom: 22px;
}
.admin-modal-inner form input[type="text"],
.admin-modal-inner form input[type="password"] {
  width: 90%;
  border: 1.5px solid #e2e8f7;
  border-radius: 8px;
  padding: 12px 14px;
  margin-bottom: 14px;
  font-size: 1.04rem;
  background: #f7faff;
  outline: none;
  transition: border .18s;
}
.admin-modal-inner form input:focus {
  border: 1.5px solid #47b1ff;
  background: #fff;
}
.admin-modal-inner .btn {
  font-size: 1.07rem;
  padding: 13px 34px;
  border-radius: 8px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  background: linear-gradient(90deg, #47b1ff, #1f7cd8);
  color: #fff;
  transition: background .17s, box-shadow .14s;
  margin-right: 12px;
}
.admin-modal-inner .btn:hover {
  background: linear-gradient(90deg, #1f7cd8, #47b1ff);
  box-shadow: 0 4px 14px #47b1ff29;
  transform: translateY(-1px) scale(1.04);
}
.admin-modal-inner .btn-close {
  background: #f5f6fa;
  color: #172446;
  border: 1.5px solid #e2e8f7;
  padding: 13px 22px;
  font-weight: 500;
  border-radius: 8px;
  cursor: pointer;
  transition: background .13s, border .13s;
}
.admin-modal-inner .btn-close:hover {
  background: #e7f6ff;
  border: 1.5px solid #47b1ff;
}

</style>