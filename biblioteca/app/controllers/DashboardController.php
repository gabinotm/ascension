<?php

require_once '../app/models/Libro.php';

class DashboardController
{
    public function index()
    {
        $libro = new Libro();

        $totalLibros = $libro->totalLibros();

        require '../app/views/dashboard/index.php';
    }
}