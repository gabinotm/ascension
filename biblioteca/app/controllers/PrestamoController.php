<?php

require_once '../app/models/Prestamo.php';

class PrestamoController
{
    public function index()
    {
        $prestamo = new Prestamo();

        $prestamos = $prestamo->obtenerTodos();

        require '../app/views/prestamos/index.php';
    }
    public function create()
{
    $lectores = (new Lector())->obtenerTodos();

    $libros = (new Libro())->obtenerTodos();

    require '../app/views/prestamos/create.php';
}

public function store()
{
    $prestamo = new Prestamo();

    $prestamo->guardar($_POST);

    header("Location: ?url=prestamos");
    exit;
}

public function edit()
{
    $prestamo = new Prestamo();

    $prestamoEditar =
        $prestamo->obtenerPorId($_GET['id']);

    $lectores = (new Lector())->obtenerTodos();

    $libros = (new Libro())->obtenerTodos();

    require '../app/views/prestamos/edit.php';
}

public function update()
{
    $prestamo = new Prestamo();

    $prestamo->actualizar($_POST);

    header("Location: ?url=prestamos");
    exit;
}

public function delete()
{
    $prestamo = new Prestamo();

    $prestamo->eliminar($_GET['id']);

    header("Location: ?url=prestamos");
    exit;
}
}