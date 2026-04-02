<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManager;

class ProjectController 
{
    private EntityManager $em;

    public function __construct(EntityManager $inEm)
    {
        $this->em = $inEm;
    }

    public function index() : void 
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')->from(\App\Models\Project::class, 'p');
        $search_name = trim($_GET['search_name'] ?? '');
        $search_place = trim($_GET['search_place'] ?? '');
        if(!empty($search_name)) {
            $qb->andWhere('p.project_name LIKE :name')->setParameter('name', '%' . $search_name . '%');
        }
        if(!empty($search_place)) {
            $qb->andWhere('p.place LIKE :place')->setParameter('place', '%' . $search_place . '%');
        }

        $projects = $qb->getQuery()->getResult();
        $status = $_GET['status'] ?? '';

        $alertMsg = "";
        $alertClass = "";

        if($status === 'success') {
            $alertMsg = "Проект добавлен в реестр";
            $alertClass = "success";
        } elseif($status === 'error') {
            $alertMsg = "Ошибка при добавлении проекта";
            $alertClass = "error";
        }

        require_once __DIR__ . "/../Views/index.php";
    }

    public function store() : void 
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $name = htmlspecialchars(strip_tags(trim($_POST['project_name'] ?? '')));
        $place = htmlspecialchars(strip_tags(trim($_POST['place'] ?? '')));
        $workers = filter_var($_POST['workers'] ?? 0, FILTER_VALIDATE_INT);
        $budget = filter_var($_POST['budget'] ?? 0, FILTER_VALIDATE_FLOAT);
    
        $materials = '';
        if(isset($_POST['materials']) && is_array($_POST['materials'])) {
            $clean_materials = array_map(fn($m) => htmlspecialchars(strip_tags(trim($m))), $_POST['materials']);
            $materials = implode(", ", $clean_materials);
        }

        $deadline_start = $_POST['deadline_start'] ?? '';
        $deadline_finish = $_POST['deadline_finish'] ?? '';

        $errors = [];
        if (empty($name)) {
            $errors[] = "Не заполнено имя проекта.";
        }
        if (empty($materials)) {
            $errors[] = "Не выбраны материалы (в списке нужно зажимать Ctrl).";
        }
        if ($workers < 1) {
            $errors[] = "Количество рабочих должно быть 1 или больше (получено: " . var_export($_POST['workers'] ?? '', true) . ").";
        }
        if ($budget < 0) {
            $errors[] = "Бюджет не может быть отрицательным.";
        }
        if (empty($deadline_start) || empty($deadline_finish)) {
            $errors[] = "Не заполнены сроки начала или окончания.";
        } elseif (strtotime($deadline_start) >= strtotime($deadline_finish)) {
            $errors[] = "Дата начала ($deadline_start) больше или равна дате конца ($deadline_finish).";
        }

        if (!empty($errors)) {
            echo "<div style='background: #fff0f0; padding: 20px; border: 1px solid red;'>";
            echo "<h3>Данные не прошли проверку:</h3><ul>";
            foreach($errors as $err) { 
                echo "<li>$err</li>"; 
            }
            echo "</ul><a href='/'>Вернуться назад</a></div>";
            exit();
        }

        try {
            $project = new \App\Models\Project();
            $project->set_project_name($name);
            $project->set_materials($materials);
            $project->set_workers_count($workers);
            $project->set_budget((string)$budget);
            $project->set_place($place);
            $project->set_deadline_start(new \DateTimeImmutable($deadline_start));
            $project->set_deadline_finish(new \DateTimeImmutable($deadline_finish));

            $this->em->persist($project);
            $this->em->flush();

            header("Location: /?status=success");
            exit();
        } catch (\Exception $ex) {
            echo "<div style='background: #fff0f0; padding: 20px; border: 1px solid red;'>";
            echo "<h3>Ошибка при сохранении в БД:</h3>";
            echo "<p>" . $ex->getMessage() . "</p>";
            echo "<a href='/'>Вернуться назад</a></div>";
            exit();
        }
    }
}

?>
