<?php

// require_once 'db.php';
require_once __DIR__ . '/bootstrap.php';

use App\Entity\Project;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$project_name = trim($_POST['project_name'] ?? '');

if (isset($_POST['materials']) && is_array($_POST['materials'])) {
    $materials = implode(", ", $_POST['materials']);
} else {
    $materials = '';
}

$workers = $_POST['workers_count'] ?? 0;
$budget = $_POST['budget'] ?? 0;
$deadline_start = $_POST['deadline_start'] ?? '';
$deadline_finish = $_POST['deadline_finish'] ?? '';
$place = $_POST['place'] ?? '';

if (
    empty($project_name) || empty($materials) || $workers < 1 || $budget < 0 ||
    strtotime($deadline_start) >= strtotime($deadline_finish)
) {
    header("Location: index.php?status=error");
    exit();
}

$project = new Project();
$project->set_project_name($project_name);
$project->set_materials($materials);
$project->set_workers_count($workers);
$project->set_budget($budget);
$project->set_deadline_start(new \DateTimeImmutable($deadline_start));
$project->set_deadline_finish(new \DateTimeImmutable($deadline_finish));
$project->set_place($place);

try {
    $entityManager->persist($project);
    $entityManager->flush();
    header("Location: index.php?status=success");
    exit();
} catch (UniqueConstraintViolationException $ex) {
    header("Location: index.php?status=error");
    exit();
} catch (\Exception $ex) {
    die("Ошибка при сохранении в базу данных" . $ex->getMessage());
}

?>
