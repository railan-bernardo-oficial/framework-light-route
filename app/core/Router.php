<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $middlewares = [];
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    // Método para adicionar rotas de qualquer método HTTP
    public function addRoute(string $method, string $path, array $callback)
    {
        $this->routes[strtoupper($method)][$path] = $callback;
    }

    // Rotas específicas para GET e POST
    public function get(string $path, array $callback)
    {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, array $callback)
    {
        return $this->addRoute('POST', $path, $callback);
    }

    // Adicionar middlewares
    public function use($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    // Executar os middlewares
    protected function executeMiddlewares()
    {
        foreach ($this->middlewares as $middleware) {
            $middleware($this->request, $this->response);
        }
    }

    // Executar a rota correspondente
    public function exec()
    {
        // Executa middlewares antes de processar a rota
        $this->executeMiddlewares();

        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        // Validação de métodos HTTP
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            return $this->response->sendError(405, "Method Not Allowed");
        }

        $callback = $this->matchRoute($method, $path);

        if (!$callback) {
            return $this->response->sendError(404, "Page Not Found");
        }

        if (is_array($callback)) {
            $controller = new $callback[0]($this->request, $this->response); // Injeção de dependências
            $method = $callback[1];
            $params = $callback[2] ?? [];

            // Invocando o método do controller
            return call_user_func_array([$controller, $method], $params);
        }

        // Caso contrário, executa a callback diretamente
        return call_user_func($callback, $this->request, $this->response);
    }

    // Encontrar a rota correspondente ao método e ao caminho
    protected function matchRoute(string $method, string $path)
    {
        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $route => $callback) {
            // Transforma a rota em uma regex para capturar parâmetros
            $regex = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $route);

            if (preg_match('#^' . $regex . '$#', $path, $matches)) {
                // Filtrar os parâmetros capturados
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [$callback[0], $callback[1], $params];
            }
        }

        return false;
    }
}
