<h1>Kedvencek</h1>
<table>
    <tr>
        <th><a href="?page=favorites&sort=name_asc">Név ↑</a> | <a href="?page=favorites&sort=name_desc">↓</a></th>
        <th><a href="?page=favorites&sort=symbol_asc">Szimbólum ↑</a> | <a href="?page=favorites&sort=symbol_desc">↓</a></th>
        <th><a href="?page=favorites&sort=current_price_asc">Ár ↑</a> | <a href="?page=favorites&sort=current_price_desc">↓</a></th>
        <th>Művelet</th>
    </tr>
    <?php foreach ($favorites as $f): ?>
        <tr>
            <td>
                <?php if ($f['icon_url']): ?>
                    <img src="<?= htmlspecialchars($f['icon_url']) ?>" alt="" style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">
                <?php endif; ?>
                <?= htmlspecialchars($f['name']) ?>
            </td>
            <td><?= htmlspecialchars($f['symbol']) ?></td>
            <td><?= htmlspecialchars($f['current_price']) ?></td>
            <td><a href="?page=favorites_delete&id=<?= $f['id'] ?>">Törlés</a></td>
        </tr>
    <?php endforeach; ?>
</table>