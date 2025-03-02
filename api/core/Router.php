<?php

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->defineRoutes();
    }

    private function defineRoutes()
    {
        $this->routes = [
            'GET' => [
                'students' => [StudentController::class, 'getAllStudents'],
                'students/{id}' => [StudentController::class, 'getStudentById'],
                'students/grades' => [StudentController::class, 'getAllGrades'],
            ],
            'POST' => [
                'students' => [StudentController::class, 'addStudent'],
            ],
            'PUT' => [
                'students/{id}' => [StudentController::class, 'updateStudent'],
            ],
            'DELETE' => [
                'students/{id}' => [StudentController::class, 'deleteStudent'],
            ],
        ];
    }

    public function handleRequest()
    {
        $requestUri = $_GET['url'] ?? '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        error_log("Requested URI: " . $requestUri);

        foreach ($this->routes[$requestMethod] as $route => $handler) {
            $pattern = preg_replace('/\{id\}/', '(\d+)', $route);
            if (preg_match("#^$pattern$#", $requestUri, $matches)) {
                [$controller, $method] = $handler;
                $controllerInstance = new $controller();

                $requestData = null;
                if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
                    $requestData = json_decode(file_get_contents("php://input"), true);
                }

                if (isset($matches[1])) {
                    return $controllerInstance->$method($matches[1], $requestData);
                }

                return $controllerInstance->$method($requestData);
            }
        }

        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
    }
}
