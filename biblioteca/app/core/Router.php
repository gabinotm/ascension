<?php

class Router
{
    public static function route()
    {
        $url = $_GET['url'] ?? 'dashboard';

        switch ($url) {
            case 'libros':
                require '../app/views/libros/index.php';
                break;

            case 'usuarios':
                require '../app/views/usuarios/index.php';
                break;

            default:
                require '../app/views/dashboard/index.php';

            case 'libros/delete':

                $controller = new LibroController();
                $controller->delete();

                break;
        }
    }
}
