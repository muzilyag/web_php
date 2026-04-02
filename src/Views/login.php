<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <section class="container">
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert error" style="color: red; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php elseif (!empty($_GET['success'])): ?>
            <div class="alert success" style="color: lightgreen; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <h2 style="text-align: center;">Вход в систему</h2>
            <div class="input_field">
                <label>Логин</label>
                <input type="text" name="username" required>
            </div>
            <div class="input_field">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
            <p style="text-align: center; font-size: 1.2rem;">
                Нет аккаунта? <a href="/register" style="color: var(--main); font-weight: bold;">Регистрация</a>
            </p>
        </form>
    </section>
</body>
</html>
