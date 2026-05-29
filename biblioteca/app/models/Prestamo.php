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
        $sql = "SELECT
                    p.*,
                    l.nombre,
                    l.apellido,
                    b.titulo
                FROM prestamos p
                INNER JOIN lectores l
                    ON p.lector_id = l.id
                INNER JOIN libros b
                    ON p.libro_id = b.id
                ORDER BY p.id DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM prestamos
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($datos)
    {
        $sqlStock = "SELECT cantidad_disponible
             FROM libros
             WHERE id = :id";

$stmtStock = $this->db->prepare($sqlStock);

$stmtStock->execute([
    ':id' => $datos['libro_id']
]);

$libro = $stmtStock->fetch(PDO::FETCH_ASSOC);

if($libro['cantidad_disponible'] <= 0){
    return false;
}
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
                    'Prestado'
                )";

        $stmt = $this->db->prepare($sql);

        $resultado = $stmt->execute([
            ':lector_id' => $datos['lector_id'],
            ':libro_id' => $datos['libro_id'],
            ':fecha_prestamo' => $datos['fecha_prestamo'],
            ':fecha_devolucion' => $datos['fecha_devolucion']
        ]);

        if ($resultado) {

            $sqlLibro = "UPDATE libros
                        SET cantidad_disponible =
                        cantidad_disponible - 1
                        WHERE id = :id";

            $stmtLibro = $this->db->prepare($sqlLibro);

            $stmtLibro->execute([
                ':id' => $datos['libro_id']
            ]);
        }

        return $resultado;
    }

    public function actualizar($datos)
    {
        $sql = "UPDATE prestamos SET

                lector_id = :lector_id,
                libro_id = :libro_id,
                fecha_prestamo = :fecha_prestamo,
                fecha_devolucion = :fecha_devolucion

                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $datos['id'],
            ':lector_id' => $datos['lector_id'],
            ':libro_id' => $datos['libro_id'],
            ':fecha_prestamo' => $datos['fecha_prestamo'],
            ':fecha_devolucion' => $datos['fecha_devolucion']
        ]);
    }

    public function devolver($id)
    {
        $sql = "SELECT libro_id
                FROM prestamos
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql2 = "UPDATE prestamos
                 SET
                    estado='Devuelto',
                    fecha_entrega=CURDATE()
                 WHERE id=:id";

        $stmt2 = $this->db->prepare($sql2);

        $resultado = $stmt2->execute([
            ':id' => $id
        ]);

        if ($resultado) {

            $sql3 = "UPDATE libros
                     SET cantidad_disponible =
                     cantidad_disponible + 1
                     WHERE id=:id";

            $stmt3 = $this->db->prepare($sql3);

            $stmt3->execute([
                ':id' => $prestamo['libro_id']
            ]);
        }

        return $resultado;
    }

    public function totalPrestamos()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM prestamos";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalVencidos()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM prestamos
                WHERE estado='Prestado'
                AND fecha_devolucion < CURDATE()";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function eliminar($id)
{
    $sql = "SELECT *
            FROM prestamos
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

    if(empty($prestamo['fecha_entrega']))
    {
        $sqlStock = "UPDATE libros
                     SET cantidad_disponible =
                     cantidad_disponible + 1
                     WHERE id = :id";

        $stmtStock = $this->db->prepare($sqlStock);

        $stmtStock->execute([
            ':id' => $prestamo['libro_id']
        ]);
    }

    $sqlDelete = "DELETE FROM prestamos
                  WHERE id = :id";

    $stmtDelete = $this->db->prepare($sqlDelete);

    return $stmtDelete->execute([
        ':id' => $id
    ]);
}
}
