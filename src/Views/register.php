<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <section class="container">
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert error" style="color: red; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <h2 style="text-align: center;">Регистрация</h2>
            <div class="input_field">
                <label>Логин</label>
                <input type="text" name="username" required>
            </div>
            <div class="input_field">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
            <p style="text-align: center; font-size: 1.2rem;">
                Уже есть аккаунт? <a href="/login" style="color: var(--main); font-weight: bold;">Войти</a>
            </p>
        </form>
    </section>
</body>
</html>
