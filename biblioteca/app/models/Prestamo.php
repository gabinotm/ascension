<?php

require_once '../config/database.php';

class Prestamo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function obtenerTodos()
    {
        $sql = "
        SELECT

            p.*,

            l.nombre,
            l.apellido,

            b.titulo

        FROM prestamos p

        INNER JOIN lectores l
        ON p.lector_id = l.id

        INNER JOIN libros b
        ON p.libro_id = b.id

        ORDER BY p.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM prestamos WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO prestamos
    (
        lector_id,
        libro_id,
        fecha_prestamo,
        fecha_devolucion,
        estado
    )
    VALUES
    (
        :lector_id,
        :libro_id,
        :fecha_prestamo,
        :fecha_devolucion,
        :estado
    )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':lector_id' => $datos['lector_id'],
            ':libro_id' => $datos['libro_id'],
            ':fecha_prestamo' => $datos['fecha_prestamo'],
            ':fecha_devolucion' => $datos['fecha_devolucion'],
            ':estado' => $datos['estado']
        ]);
    }

    public function actualizar($datos)
    {
        $sql = "UPDATE prestamos SET

        lector_id = :lector_id,
        libro_id = :libro_id,
        fecha_prestamo = :fecha_prestamo,
        fecha_devolucion = :fecha_devolucion,
        estado = :estado

    WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $datos['id'],
            ':lector_id' => $datos['lector_id'],
            ':libro_id' => $datos['libro_id'],
            ':fecha_prestamo' => $datos['fecha_prestamo'],
            ':fecha_devolucion' => $datos['fecha_devolucion'],
            ':estado' => $datos['estado']
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM prestamos WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
