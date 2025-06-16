<?php
// $user_id має бути заданий!
$today = date('Y-m-d H:i:s');
$q = $pdo->prepare("SELECT b.id, s.session_date, c.name as class_name, t.full_name as trainer, b.status
                    FROM bookings b
                    JOIN sessions s ON b.session_id = s.id
                    JOIN classes c ON s.class_id = c.id
                    JOIN trainers t ON s.trainer_id = t.id
                    WHERE b.member_id=?
                    ORDER BY s.session_date DESC");
$q->execute([$user_id]);
$bookings = $q->fetchAll();
?>

<h3>Мої заняття</h3>
<?php if (!$bookings): ?>
    <div class="empty-message">У вас поки що немає записів.</div>
<?php else: ?>
    <div class="bookings-table-wrapper">
    <table class="bookings-table">
    <thead>
      <tr>
        <th>Дата</th>
        <th>Час</th>
        <th>Заняття</th>
        <th>Тренер</th>
        <th>Статус</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($bookings as $b):
        $date = date('d.m.Y', strtotime($b['session_date']));
        $time = date('H:i', strtotime($b['session_date']));
        $isFuture = strtotime($b['session_date']) > strtotime('now');
    ?>
    <tr>
        <td><?=$date?></td>
        <td><?=$time?></td>
        <td><?=htmlspecialchars($b['class_name'])?></td>
        <td><?=htmlspecialchars($b['trainer'])?></td>
        <td>
            <?php if ($b['status'] == 'cancelled'): ?>
                <span class="status status-cancelled">Скасовано</span>
            <?php elseif ($isFuture): ?>
                <span class="status status-upcoming">Заплановано</span>
            <?php else: ?>
                <span class="status status-past">Завершено</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($isFuture && $b['status'] !== 'cancelled'): ?>
                <form method="post" action="cancel_booking.php" style="display:inline;">
                    <input type="hidden" name="booking_id" value="<?=$b['id']?>">
                    <button type="submit" class="btn btn-small" onclick="return confirm('Скасувати запис?')">Відмінити</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    </div>
<?php endif; ?>

