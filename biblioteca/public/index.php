<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../app/controllers/LibroController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/LectorController.php';
require_once '../app/controllers/PrestamoController.php';

$url = $_GET['url'] ?? 'dashboard';

switch ($url) {

    case 'dashboard':

        $controller = new DashboardController();
        $controller->index();

        break;

    case 'libros':

        $controller = new LibroController();
        $controller->index();

        break;

    case 'libros/create':

        $controller = new LibroController();
        $controller->create();

        break;

    case 'libros/store':

        $controller = new LibroController();
        $controller->store();

        break;

    case 'libros/edit':

        $controller = new LibroController();
        $controller->edit();

        break;

    case 'libros/update':

        $controller = new LibroController();
        $controller->update();

        break;

    case 'libros/delete':

        $controller = new LibroController();
        $controller->delete();

        break;

    default:

        echo "<h1>Dashboard Biblioteca</h1>";
        echo '<p><a href="?url=libros">Ir a Libros</a></p>';

        break;
    case 'lectores':

        $controller = new LectorController();
        $controller->index();

        break;

    case 'lectores/create':

        $controller = new LectorController();
        $controller->create();

        break;

    case 'lectores/store':

        $controller = new LectorController();
        $controller->store();

        break;

    case 'lectores/edit':

        $controller = new LectorController();
        $controller->edit();

        break;

    case 'lectores/update':

        $controller = new LectorController();
        $controller->update();

        break;

    case 'lectores/delete':

        $controller = new LectorController();
        $controller->delete();

        break;
    case 'prestamos/create':

        $controller = new PrestamoController();
        $controller->create();

        break;

    case 'prestamos/store':

        $controller = new PrestamoController();
        $controller->store();

        break;

    case 'prestamos/edit':

        $controller = new PrestamoController();
        $controller->edit();

        break;

    case 'prestamos/update':

        $controller = new PrestamoController();
        $controller->update();

        break;

    case 'prestamos/delete':

        $controller = new PrestamoController();
        $controller->delete();

        break;
}
