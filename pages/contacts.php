<?php 
$page_class = 'contacts';
include 'templates/header.php'; 
?>
<main>
  <section class="contacts-section">
    <h1 class="contacts-title">Контакти</h1>
    <div class="contacts-info">
      <p><b>Адреса:</b> вул. Фітнесова, 10, м. Місто</p>
      <p><b>Телефон:</b> <a href="tel:+380991234567">+38 (099) 123-45-67</a></p>
      <p><b>Email:</b> <a href="mailto:info@powerfit.ua">info@powerfit.ua</a></p>
    </div>
    <h2 class="contacts-subtitle">Зв’язатися з нами</h2>
    <form method="post" class="contact-form">
      <input type="text" name="name" placeholder="Ваше ім'я" required>
      <input type="email" name="email" placeholder="Email" required>
      <textarea name="message" placeholder="Ваше повідомлення" required></textarea>
      <button type="submit" class="btn btn-primary">Відправити</button>
    </form>
  </section>
</main>
<?php include 'templates/footer.php'; ?>
