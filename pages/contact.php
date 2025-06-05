<?php require_once '../templates/header.php'; ?>
<h2>Контакти</h2>
<p>Адреса: вул. Головна 310, Чернівці<br>
Телефон: +380 44 123 4567<br>
Email: info@powerfit.ua</p>
<form>
    <label>Ваше ім'я: <input type="text" name="name"></label><br>
    <label>Email: <input type="email" name="email"></label><br>
    <label>Повідомлення:<br>
        <textarea name="message" rows="5"></textarea>
    </label><br>
    <button type="submit">Відправити</button>
</form>
<?php require_once '../templates/footer.php'; ?>
