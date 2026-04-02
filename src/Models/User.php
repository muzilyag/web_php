<?php 

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int | null $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password_hash;

    // Роли: user или admin
    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'user'])]
    private string $role = 'user';

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_username(): string
    {
        return $this->username;
    }

    public function set_username(string $username): void
    {
        $this->username = $username;
    }

    public function get_password_hash(): string
    {
        return $this->password_hash;
    }

    public function set_password_hash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function get_role(): string
    {
        return $this->role;
    }

    public function set_role(string $role): void
    {
        $this->role = $role;
    }
}
