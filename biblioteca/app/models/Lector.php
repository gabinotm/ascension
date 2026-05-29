<?php

require_once '../config/database.php';

class Lector
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM lectores ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO lectores
        (
            dni,
            nombre,
            apellido,
            correo,
            telefono
        )
        VALUES
        (
            :dni,
            :nombre,
            :apellido,
            :correo,
            :telefono
        )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':dni' => $datos['dni'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono']
        ]);
    }
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM lectores WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($datos)
    {
        $sql = "UPDATE lectores SET

        dni = :dni,
        nombre = :nombre,
        apellido = :apellido,
        correo = :correo,
        telefono = :telefono

        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $datos['id'],
            ':dni' => $datos['dni'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono']
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM lectores WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
