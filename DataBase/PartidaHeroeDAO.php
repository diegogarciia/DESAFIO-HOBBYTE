<?php
require_once __DIR__ . '/../DataBase/Database.php';
require_once __DIR__ . '/../Model/PartidaHeroe.php';

class PartidaHeroeDAO {

    public static function insert($id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado) {
        $conexion = Database::connect();
        $sql = "INSERT INTO partida_heroe (id_partida, id_heroe, poder_inicial, poder_actual, activo, derrotado)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiiiii", $id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conexion->close();
        return $id;
    }

    public static function getByPartida($id_partida) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM partida_heroe WHERE id_partida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $heroes = [];

        while ($fila = $resultado->fetch_assoc()) {
            $heroes[] = new PartidaHeroe(
                $fila['id_partida_heroe'],
                $fila['id_partida'],
                $fila['id_heroe'],
                $fila['poder_inicial'],
                $fila['poder_actual'],
                $fila['activo'],
                $fila['derrotado']
            );
        }

        $stmt->close();
        $conexion->close();
        return $heroes;
    }

    public static function updatePoderActual($id_partida_heroe, $nuevo_poder) {
        $conexion = Database::connect();
        $sql = "UPDATE partida_heroe SET poder_actual = ? WHERE id_partida_heroe = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $nuevo_poder, $id_partida_heroe);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    public static function updateDerrotado($id_partida_heroe) {
        $conexion = Database::connect();
        $sql = "UPDATE partida_heroe SET derrotado = 1, activo = 0 WHERE id_partida_heroe = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_partida_heroe);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }
}
?>
