<?php include 'templates/header.php'; ?>
<div class="container">
    <h2>Контакти</h2>
    <div class="contacts-info">
        <p><strong>Адреса:</strong> вул. Фітнесова, 10, м. Місто</p>
        <p><strong>Телефон:</strong> +38 (099) 123-45-67</p>
        <p><strong>Email:</strong> info@powerfit.ua</p>
    </div>
    <h3>Зв’язатися з нами</h3>
    <form method="post" class="contact-form">
        <input type="text" name="name" placeholder="Ваше ім'я" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="message" placeholder="Ваше повідомлення" required></textarea>
        <button type="submit">Відправити</button>
    </form>
</div>
<?php include 'templates/footer.php'; ?>
