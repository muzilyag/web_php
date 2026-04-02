<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LR_WEB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="/style.css" />
    
</head>

<body>
    <section class="container">
        <?php if ($alertMsg): ?>
            <div class="alert <?= $alertClass ?>">
                <?= $alertMsg ?>
            </div>
        <?php endif; ?>
        <div style="text-align: right; padding: 10px; color: var(--text);"> 
            Вы вошли как: <b><?= htmlspecialchars($_SESSION['username'] ?? '') ?></b> (Роль: <?= htmlspecialchars($_SESSION['role'] ?? '') ?>) | 
        <a href="/logout" style="color: var(--button); font-weight: bold;">Выйти</a>
        </div>
        <form action="/index.php/add" method="POST">
            <h2>Строительный проект</h2>
            <div class="input_field">
                <label>Имя проекта</label>
                <input type="text" name="project_name" placeholder="Название объекта" required />
            </div>
            <div class="input_field">
                <label>Материалы</label>
                <select name="materials[]" multiple size="4">
                    <option value="concrete">Бетон</option>
                    <option value="brick">Кирпич</option>
                    <option value="iron">Железо</option>
                    <option value="wood">Дерево</option>
                </select>
            </div>
            <div class="input_field">
                <label>Кол-во рабочих</label>
                <input type="number" name="workers" placeholder="100" required />
            </div>
            <div class="input_field">
                <label>Бюджет</label>
                <input type="number" step="0.1" name="budget" placeholder="100000" required />
            </div>
            <div class="input_field">
                <label>Сроки</label>
                <div>
                    <input type="date" name="deadline_start" placeholder="dd-mm-yyyy"/>
                    <input type="date" name="deadline_finish" />
                </div>
            </div>
            <div class="input_field input_map">
                <div> 
                    <label>Расположение</label>
                    <input type="text" name="place" id="place_input" placeholder="Город, улица или координаты" />
                </div>
                <div id="map"></div>            
            </div>
            <button type="submit">Добавить</button>
        </form>
    </section>
    <section class="filter">
        <h3>Фильтр проектов</h3>
        <form action="/" method="GET">
            <input type="text" name="search_name" placeholder="Название проекта..." value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>" />
            <input type="text" name="search_place" placeholder="Место стройки..." value="<?= htmlspecialchars($_GET['search_place'] ?? '') ?>" />
            <button type="submit">Найти</button>
            <a href="/">Сбросить</a>
        </form>
    </section>
    <section class="db_table">
        <h2>Список проектов в базе данных</h2>
        <?php if (empty($projects)): ?>
            <p>Записей пока нет</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Материалы</th>
                        <th>Рабочие</th>
                        <th>Бюджет</th>
                        <th>Начало работ</th>
                        <th>Конец работ</th>
                        <th>Место</th>
                        <th>Время добавления записи</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= $project->get_id() ?></td>
                            <td><?= htmlspecialchars($project->get_project_name()) ?></td>
                            <td><?= htmlspecialchars($project->get_materials()) ?></td>
                            <td><?= htmlspecialchars($project->get_workers_count()) ?></td>
                            <td><?= number_format((float)$project->get_budget(), 2, '.', ' ') ?> руб</td>
                            <td><?= $project->get_deadline_start() ? $project->get_deadline_start()->format('Y-m-d') : '-' ?></td>
                            <td><?= $project->get_deadline_finish() ? $project->get_deadline_finish()->format('Y-m-d') : '-' ?></td>
                            <td><?= htmlspecialchars($project->get_place()) ?></td>
                            <td><?= $project->get_created_at()->format('d-m-Y H:i:s') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
    <script src="/map.js"></script>
</body>

</html>
