<?php

require_once '../config/database.php';

class Libro
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM libros ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO libros
        (
            isbn,
            titulo,
            autor,
            cantidad_total,
            cantidad_disponible
        )
        VALUES
        (
            :isbn,
            :titulo,
            :autor,
            :cantidad_total,
            :cantidad_disponible
        )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':isbn' => $datos['isbn'],
            ':titulo' => $datos['titulo'],
            ':autor' => $datos['autor'],
            ':cantidad_total' => $datos['cantidad_total'],
            ':cantidad_disponible' => $datos['cantidad_disponible']
        ]);
    }
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM libros WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($datos)
    {
        $sql = "UPDATE libros SET

        isbn = :isbn,
        titulo = :titulo,
        autor = :autor,
        cantidad_total = :cantidad_total,
        cantidad_disponible = :cantidad_disponible

        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $datos['id'],
            ':isbn' => $datos['isbn'],
            ':titulo' => $datos['titulo'],
            ':autor' => $datos['autor'],
            ':cantidad_total' => $datos['cantidad_total'],
            ':cantidad_disponible' => $datos['cantidad_disponible']
        ]);
    }
    public function eliminar($id)
    {
        $sql = "DELETE FROM libros WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
    public function totalLibros()
    {
        $sql = "SELECT COUNT(*) AS total FROM libros";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function existeISBN($isbn)
{
    $sql = "SELECT id FROM libros WHERE isbn = :isbn";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':isbn' => $isbn
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function buscarPorISBN($isbn)
{
    $sql = "SELECT *
            FROM libros
            WHERE isbn = :isbn";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':isbn' => $isbn
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}