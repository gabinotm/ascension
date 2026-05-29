<?php

require_once '../app/models/Libro.php';

class InventarioController
{
    public function index()
    {
        require '../app/views/inventario/index.php';
    }

   

    public function buscar()
    {
        $isbn = $_GET['isbn'] ?? '';

        $libroModel = new Libro();

        $libro = $libroModel->buscarPorISBN($isbn);

        if($libro)
        {
            header(
                "Location: ?url=libros/edit&id=".$libro['id']
            );
        }
        else
        {
            echo "Libro no encontrado";
        }
    }
}