<?php 

namespace App\Controllers;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class AuthController 
{
    private EntityManager $em;

    public function __construct(EntityManager $inEm)
    {
        $this->em = $inEm;
    }

    public function show_register() : void
    {
        require_once __DIR__ . "/../Views/register.php";
    }

    public function register() : void 
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if(empty($username) || empty($password)) {
            header("Location: /register?error=Заполните все поля");
            exit();
        }

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
        if($existingUser) {
            header("Location: /register?error=Пользователь уже существует");
            exit();
        }

        $user = new User();
        $user->set_username($username);

        $user->set_password_hash(password_hash($password, PASSWORD_DEFAULT));
        $user->set_role("user");

        $this->em->persist($user);
        $this->em->flush();

        $_SESSION['user_id'] = $user->get_id();
        $_SESSION['username'] = $user->get_username();
        $_SESSION['role'] = $user->get_role();

        header("Location: /");
        exit();
    }

    public function show_login() : void 
    {
        require_once __DIR__ . "/../Views/login.php";
    }

    public function login() : void 
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if(empty($username) || empty($password)) {
            header("Location: /login?error=Заполните все поля");
            exit();
        }
        
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($user && password_verify($password, $user->get_password_hash())) {
            $_SESSION['user_id'] = $user->get_id();
            $_SESSION['username'] = $user->get_username();
            $_SESSION['role'] = $user->get_role();

            header("Location: /");
            exit();
        }

        header("Location: /login?error=Неверный логин или пароль");
        exit();
    }

    public function logout() : void 
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}
