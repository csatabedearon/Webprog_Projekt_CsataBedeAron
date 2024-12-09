<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>CryptoApp</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
        <a href="?page=cryptocurrencies">Kriptók</a>
        <?php if (isLoggedIn()): ?>
            <a href="?page=favorites">Kedvencek</a>
            <a href="?page=notifications">Értesítések</a>
            <a href="?page=update_prices" class="btn-update">Adatok frissítése</a>
            <a href="?page=logout" style="background:#f33;color:#fff;padding:5px 10px;border-radius:3px;text-decoration:none;margin-left:10px;">
                Kijelentkezés
            </a>
        <?php else: ?>
            <a href="?page=login">Bejelentkezés</a>
            <a href="?page=register">Regisztráció</a>
        <?php endif; ?>
    </nav>


    <main>
        <?php if (!empty($errors)): ?>
            <div class="error-messages" style="background:#fdd; border:1px solid #f99; padding:10px; margin-bottom:20px;">
                <ul style="margin:0; padding:0; list-style:none;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . "/$template.php"; ?>
    </main>


</body>

</html>