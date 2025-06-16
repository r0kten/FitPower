<?php
session_start();
require_once '../db.php';

$sessions = $pdo->query("
  SELECT s.*, c.name AS class_name, t.full_name AS trainer_name,
         (SELECT COUNT(*) FROM bookings b WHERE b.session_id = s.id) as booked_count
  FROM sessions s
  JOIN classes c ON s.class_id = c.id
  JOIN trainers t ON s.trainer_id = t.id
  WHERE s.session_date >= NOW()
  ORDER BY s.session_date
")->fetchAll();

$already_booked = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $b = $pdo->query("SELECT session_id FROM bookings WHERE member_id = $uid")->fetchAll(PDO::FETCH_COLUMN);
    $already_booked = array_flip($b);
}
$page_class='schedule';
include 'templates/header.php';
?>
<main>
  <section class="schedule-section">
    <h2 class="schedule-title">Розклад занять</h2>
    <div class="schedule-table-wrapper">
      <table class="schedule-table">
        <thead>
          <tr>
              <th>Дата</th>
              <th>Час</th>
              <th>Заняття</th>
              <th>Тренер</th>
              <th>Вільно місць</th>
              <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($sessions as $s): ?>
          <tr>
            <td><?=date('d.m.Y', strtotime($s['session_date']))?></td>
            <td><?=date('H:i', strtotime($s['session_date']))?> <span class="schedule-duration">(<?=$s['duration']?> хв)</span></td>
            <td><?=htmlspecialchars($s['class_name'])?></td>
            <td><?=htmlspecialchars($s['trainer_name'])?></td>
            <td>
              <span class="free-places"><?=$s['capacity'] - $s['booked_count']?></span> / <?=$s['capacity']?>
            </td>
            <td>
              <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-small btn-outline-primary">Увійти</a>
              <?php elseif (($s['capacity'] - $s['booked_count']) < 1): ?>
                <span class="status status-full">Немає місць</span>
              <?php elseif (isset($already_booked[$s['id']])): ?>
                <span class="status status-booked">Ви записані</span>
              <?php else: ?>
                <form method="post" action="book.php" style="display:inline">
                  <input type="hidden" name="session_id" value="<?=$s['id']?>">
                  <button type="submit" class="btn btn-small btn-primary">Записатися</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php include 'templates/footer.php'; ?>
