<?php
require_once __DIR__ . '/../DataBase/DataBase.php';
require_once __DIR__ . '/../Model/Casilla.php';

class CasillaDAO {

    public static function insert($id_partida, $posicion, $habilidad, $esfuerzo) {
        $conexion = Database::connect();
        $sql = "INSERT INTO casilla (id_partida, posicion, habilidad, esfuerzo_requerido, destapada, exito) 
                VALUES (?, ?, ?, ?, 0, NULL)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isii", $id_partida, $posicion, $habilidad, $esfuerzo);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conexion->close();
        return $id;
    }

    public static function getByPartida($id_partida) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM casilla WHERE id_partida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $casillas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $casillas[] = new Casilla(
                $fila['id_casilla'],
                $fila['id_partida'],
                $fila['posicion'],
                $fila['habilidad'],
                $fila['esfuerzo_requerido'],
                $fila['destapada'],
                $fila['exito']
            );
        }
        
        $stmt->close();
        $conexion->close();
        return $casillas;
    }

    public static function updateDestapadaExito($id_casilla, $destapada, $exito) {
        $conexion = Database::connect();
        $sql = "UPDATE casilla SET destapada=?, exito=? WHERE id_casilla=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $destapada, $exito, $id_casilla);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    public static function getById($id_casilla) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM casilla WHERE id_casilla=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_casilla);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $casilla = null;

        if ($fila = $resultado->fetch_assoc()) {
            $casilla = new Casilla(
                $fila['id_casilla'],
                $fila['id_partida'],
                $fila['posicion'],
                $fila['habilidad'],
                $fila['esfuerzo_requerido'],
                $fila['destapada'],
                $fila['exito']
            );
        }

        $stmt->close();
        $conexion->close();
        return $casilla;
    }

    public static function getByPosicion($id_partida, $posicion) {
    $conexion = Database::connect();
    $sql = "SELECT * FROM casilla WHERE id_partida = ? AND posicion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_partida, $posicion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $casilla = null;

    if ($fila = $resultado->fetch_assoc()) {
        $casilla = new Casilla(
            $fila['id_casilla'],
            $fila['id_partida'],
            $fila['posicion'],
            $fila['habilidad'],
            $fila['esfuerzo_requerido'],
            $fila['destapada'],
            $fila['exito']
        );
    }

    $stmt->close();
    $conexion->close();
    return $casilla;
}

}
