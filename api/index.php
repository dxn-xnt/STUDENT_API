<?php
header("Content-Type: application/json");

require_once "core/Router.php";
require_once "controllers/StudentController.php";

$router = new Router();
$router->handleRequest();
