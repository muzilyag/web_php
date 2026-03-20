<?php 

namespace App;

class Router
{
    private array $routes = [];
    private Container $container;

    public function __construct(Container $inCont)
    {
        $this->container = $inCont;
    }

    public function add(string $method, string $path, array $handler) : void
    {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(string $method, string $uri) : void 
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace('/index.php', '', $path);

        if($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        if($path === '') {
            $path = '/';
        } 

        foreach($this->routes as $route) {
            if($route['method'] !== $method || $route['path'] !== $path) {
                continue;
            }

            [$controllerClass, $action] = $route['handler'];
            if(!class_exists($controllerClass)) {
                http_response_code(500);
                echo "Ошибка 500";
                return;
            }
            
            try {
                $controller = $this->container->get($controllerClass);
            } catch(\Exception $ex) {
                http_response_code(500);
                echo "Ошибка 500: " . $ex->getMessage();
                return;
            }

            if(!method_exists($controller, $action)) {
                http_response_code(500);
                echo "Ошибка 500";
                return;

            }

            $controller->$action();
            return;
        }
        echo "<div style='background: #fff0f0; padding: 20px; border: 1px solid red; font-family: sans-serif;'>";
        echo "<h3>Страница не найдена! (Ошибка Роутера)</h3>";
        echo "<p><b>Пришел метод:</b> " . htmlspecialchars($method) . "</p>";
        echo "<p><b>Очищенный путь:</b> '" . htmlspecialchars($path) . "'</p>";
        echo "<p><b>Оригинальный URI:</b> '" . htmlspecialchars($uri) . "'</p>";
        echo "<hr><p><b>Что ждет Роутер:</b></p><pre>";
        print_r($this->routes);
        http_response_code(404);
        echo "Страница не найдена! 404";
        return;
    }
}

?>
