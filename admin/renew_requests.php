<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// --- Підтвердження заявки ---
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    // Отримуємо заявку з даними про користувача та план
    $stmt = $pdo->prepare("
        SELECT r.*, m.membership_plan_id, m.membership_expires, m.id as member_id, p.duration_months
        FROM renew_requests r
        JOIN members m ON r.member_id = m.id
        JOIN membership_plans p ON m.membership_plan_id = p.id
        WHERE r.id = ?
    ");
    $stmt->execute([$id]);
    $req = $stmt->fetch();

    if ($req) {
        // Визначаємо базову дату для продовження
        $current_exp = $req['membership_expires'];
        $base_date = ($current_exp && strtotime($current_exp) > time()) ? $current_exp : date('Y-m-d');
        // Додаємо місяці до дати
        $new_expires = (new DateTime($base_date))->modify("+{$req['duration_months']} months")->format('Y-m-d');

        // Оновлюємо members
        $pdo->prepare("UPDATE members SET membership_expires=? WHERE id=?")->execute([$new_expires, $req['member_id']]);
        // Статус заявки
        $pdo->prepare("UPDATE renew_requests SET status='approved' WHERE id=?")->execute([$id]);
    }
    header("Location: renew_requests.php");
    exit;
}

// --- Відхилення заявки ---
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $pdo->prepare("UPDATE renew_requests SET status='rejected' WHERE id=?")->execute([$id]);
    header("Location: renew_requests.php");
    exit;
}

// --- Вивід списку ---
$reqs = $pdo->query("
    SELECT r.*, m.full_name, m.email, p.name as plan_name, p.duration_months, r.status
    FROM renew_requests r
    JOIN members m ON r.member_id = m.id
    JOIN membership_plans p ON m.membership_plan_id = p.id
    ORDER BY r.request_date DESC
")->fetchAll();
?>

<?php include 'templates/header.php'; ?>
<h2>Заявки на продовження абонементів</h2>
<table>
    <tr>
        <th>Клієнт</th>
        <th>Email</th>
        <th>План</th>
        <th>Дата заявки</th>
        <th>Статус</th>
        <th>Дія</th>
    </tr>
    <?php foreach($reqs as $r): ?>
    <tr>
        <td><?=htmlspecialchars($r['full_name'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td><?=htmlspecialchars($r['plan_name'])?> (<?=$r['duration_months']?> міс.)</td>
        <td><?=htmlspecialchars($r['request_date'])?></td>
        <td>
            <?php if($r['status']=='approved'): ?>
                <span style="color:green;">Підтверджено</span>
            <?php elseif($r['status']=='rejected'): ?>
                <span style="color:red;">Відхилено</span>
            <?php else: ?>
                <span style="color:orange;">Очікує</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if($r['status']=='pending'): ?>
                <a href="?approve=<?=$r['id']?>" onclick="return confirm('Підтвердити заявку?')">✔️</a>
                <a href="?reject=<?=$r['id']?>" onclick="return confirm('Відхилити заявку?')">❌</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include 'templates/footer.php'; ?>
