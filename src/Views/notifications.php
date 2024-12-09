<h1>Értesítések</h1>
<form method="POST" action="?page=notifications_store">
    <label>Kriptó ID: <input type="text" name="cryptocurrency_id"></label><br>
    <label>Trigger ár: <input type="text" name="trigger_price"></label><br>
    <button type="submit">Hozzáadás</button>
</form>

<table>
    <tr>
        <th><a href="?page=notifications&sort=name_asc">Kriptó ↑</a> | <a href="?page=notifications&sort=name_desc">↓</a></th>
        <th><a href="?page=notifications&sort=current_price_asc">Aktuális ár ↑</a> | <a href="?page=notifications&sort=current_price_desc">↓</a></th>
        <th>Trigger ár</th>
        <th>Értesítve?</th>
        <th>Művelet</th>
    </tr>
    <?php foreach ($notifications as $n): ?>
        <tr>
            <td>
                <?php if ($n['icon_url']): ?>
                    <img src="<?= htmlspecialchars($n['icon_url']) ?>" alt="" style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">
                <?php endif; ?>
                <?= htmlspecialchars($n['name']) ?> (<?= htmlspecialchars($n['symbol']) ?>)
            </td>
            <td><?= htmlspecialchars($n['current_price']) ?></td>
            <td><?= htmlspecialchars($n['trigger_price']) ?></td>
            <td><?= $n['notification_sent'] ? 'Igen' : 'Nem' ?></td>
            <td><a href="?page=notifications_delete&id=<?= $n['id'] ?>">Törlés</a></td>
        </tr>
    <?php endforeach; ?>
</table>