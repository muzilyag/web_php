<?php 

namespace App;

use Exception;

class Container 
{
    private array $services = [];

    public function set(string $id, callable $factory) : void 
    {
        $this->services[$id] = $factory;
    }

    public function get(string $id) : mixed
    {
        if(!isset($this->services[$id])) {
            throw new Exception("Сервис {$id} не найден!");
        }
        return $this->services[$id]($this);
    }
}

?>
