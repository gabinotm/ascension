<?php

require_once '../app/models/Prestamo.php';
require_once '../app/models/Lector.php';
require_once '../app/models/Libro.php';

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
    $lectorModel = new Lector();
    $libroModel = new Libro();

    $lectores = $lectorModel->obtenerTodos();

    $libros = $libroModel->obtenerTodos();

    $libroSeleccionado =
    $_GET['libro_id'] ?? null;

    require '../app/views/prestamos/create.php';
}

    public function store()
    {
        $prestamo = new Prestamo();
        if(
    strtotime($_POST['fecha_devolucion'])
    <=
    strtotime($_POST['fecha_prestamo'])
){
    die("La fecha de devolución debe ser mayor a la fecha de préstamo");
}

        $prestamo->guardar($_POST);

        header("Location: ?url=prestamos");
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=prestamos");
            exit;
        }

        $prestamoModel = new Prestamo();
        $lectorModel = new Lector();
        $libroModel = new Libro();

        $prestamo = $prestamoModel->obtenerPorId($id);

        $lectores = $lectorModel->obtenerTodos();
        $libros = $libroModel->obtenerTodos();

        require '../app/views/prestamos/edit.php';
    }

    public function update()
    {
        $prestamo = new Prestamo();

        $prestamo->actualizar($_POST);

        header("Location: ?url=prestamos");
        exit;
    }

    public function devolver()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=prestamos");
            exit;
        }

        $prestamo = new Prestamo();

        $prestamo->devolver($id);

        header("Location: ?url=prestamos");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: ?url=prestamos");
            exit;
        }

        $prestamo = new Prestamo();

        $prestamo->eliminar($id);

        header("Location: ?url=prestamos");
        exit;
    }
 

public function buscar()
{
    $isbn = $_GET['isbn'] ?? '';

    $libroModel = new Libro();

    $libro = $libroModel->buscarPorISBN($isbn);

    if(!$libro)
    {
        die('Libro no encontrado');
    }

    header(
        'Location: ?url=prestamos/create&libro_id=' .
        $libro['id']
    );

    exit;
}

}
?>
