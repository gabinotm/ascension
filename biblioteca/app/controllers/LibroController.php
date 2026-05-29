<?php

require_once '../app/models/Libro.php';

class LibroController
{
    public function index()
    {
        $libro = new Libro();

        $libros = $libro->obtenerTodos();

        require '../app/views/libros/index.php';
    }

    public function create()
    {
        require '../app/views/libros/create.php';
    }

    public function store()
    {
        try {

            $libro = new Libro();

            $libro->guardar($_POST);

            header("Location: ?url=libros");
            exit;

        } catch (PDOException $e) {

            die("Error al guardar: " . $e->getMessage());

        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=libros");
            exit;
        }

        $libroModel = new Libro();

        $libro = $libroModel->obtenerPorId($id);

        if (!$libro) {
            die("Error: El libro no existe.");
        }

        require '../app/views/libros/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $libro = new Libro();

            $libro->actualizar($_POST);

            header("Location: ?url=libros");
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=libros");
            exit;
        }

        $libro = new Libro();

        $libro->eliminar($id);

        header("Location: ?url=libros");
        exit;
    }
}