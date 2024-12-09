<h1>Kriptovaluták</h1>

<div style="margin-bottom:20px;">
    Showing <?= $from ?> to <?= $to ?> of <?= $total ?> results
</div>

<div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <div>Rows</div>
    <form method="GET" style="display:inline;">
        <!-- Megőrizzük a sort paramétert, és a route-ot (page=cryptocurrencies), lapozást újra 1-re állítjuk -->
        <input type="hidden" name="page" value="cryptocurrencies">
        <input type="hidden" name="p" value="1">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <select name="limit" onchange="this.form.submit()">
            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
        </select>
    </form>
</div>

<table>
    <tr>
        <th>
            Név
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=name_asc">↑</a>
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=name_desc">↓</a>
        </th>
        <th>
            Szimbólum
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=symbol_asc">↑</a>
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=symbol_desc">↓</a>
        </th>
        <th>
            Ár
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=current_price_asc">↑</a>
            <a href="?page=cryptocurrencies&p=1&limit=<?= $limit ?>&sort=current_price_desc">↓</a>
        </th>
        <th>Hozzáadás kedvencekhez</th>
    </tr>
    <?php if (!empty($cryptos)): ?>
        <?php foreach ($cryptos as $c): ?>
            <tr>
                <td>
                    <?php if (!empty($c['icon_url'])): ?>
                        <img src="<?= htmlspecialchars($c['icon_url']) ?>" alt="" style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">
                    <?php endif; ?>
                    <?= htmlspecialchars($c['name']) ?>
                </td>
                <td><?= htmlspecialchars($c['symbol']) ?></td>
                <td><?= htmlspecialchars($c['current_price']) ?></td>
                <?php if (isLoggedIn()): ?>
                    <td>
                        <form method="POST" action="?page=favorites_store">
                            <input type="hidden" name="cryptocurrency_id" value="<?= $c['id'] ?>">
                            <button type="submit">Hozzáadás</button>
                        </form>
                    </td>
                <?php else: ?>
                    <td><span style="color:#999;">Jelentkezz be a kedvencekhez!</span></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Nincsenek kriptovaluták az adatbázisban.</td>
        </tr>
    <?php endif; ?>
</table>

<!-- Lapozás -->
<div style="margin-top:20px;display:flex;align-items:center;gap:5px;">
    <!-- Előző oldal -->
    <?php if ($p > 1): ?>
        <a href="?page=cryptocurrencies&p=<?= $p - 1 ?>&limit=<?= $limit ?>&sort=<?= htmlspecialchars($sort) ?>">&lt;</a>
    <?php else: ?>
        <span style="color:#ccc;">&lt;</span>
    <?php endif; ?>

    <?php
    $maxLinks = 5;
    $start = max(1, $p - 2);
    $end = min($totalPages, $p + 2);

    if ($start > 1) {
        echo '<a href="?page=cryptocurrencies&p=1&limit=' . $limit . '&sort=' . htmlspecialchars($sort) . '">1</a> ';
        if ($start > 2) echo '... ';
    }

    for ($pg = $start; $pg <= $end; $pg++) {
        if ($pg == $p) {
            echo '<span style="background:#dfffd0;padding:5px;border-radius:3px;">' . $pg . '</span> ';
        } else {
            echo '<a href="?page=cryptocurrencies&p=' . $pg . '&limit=' . $limit . '&sort=' . htmlspecialchars($sort) . '">' . $pg . '</a> ';
        }
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) echo '... ';
        echo '<a href="?page=cryptocurrencies&p=' . $totalPages . '&limit=' . $limit . '&sort=' . htmlspecialchars($sort) . '">' . $totalPages . '</a> ';
    }
    ?>

    <!-- Következő oldal -->
    <?php if ($p < $totalPages): ?>
        <a href="?page=cryptocurrencies&p=<?= $p + 1 ?>&limit=<?= $limit ?>&sort=<?= htmlspecialchars($sort) ?>">&gt;</a>
    <?php else: ?>
        <span style="color:#ccc;">&gt;</span>
    <?php endif; ?>
</div>