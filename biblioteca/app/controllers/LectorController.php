<?php

require_once '../app/models/Lector.php';

class LectorController
{
    public function index()
    {
        $lector = new Lector();

        $lectores = $lector->obtenerTodos();

        require '../app/views/lectores/index.php';
    }

    public function create()
    {
        require '../app/views/lectores/create.php';
    }

    public function store()
    {
        $lector = new Lector();

        $lector->guardar($_POST);

        header("Location: ?url=lectores");
        exit;
    }
    public function edit()
{
    $id = $_GET['id'];

    $lectorModel = new Lector();

    $lector = $lectorModel->obtenerPorId($id);

    require '../app/views/lectores/edit.php';
}

public function update()
{
    $lector = new Lector();

    $lector->actualizar($_POST);

    header("Location: ?url=lectores");
    exit;
}

public function delete()
{
    $id = $_GET['id'];

    $lector = new Lector();

    $lector->eliminar($id);

    header("Location: ?url=lectores");
    exit;
}
}