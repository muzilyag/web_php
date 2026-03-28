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
        print_r($this->routes);
        http_response_code(404);
        echo "Страница не найдена! 404";
        return;
    }
}

?>
