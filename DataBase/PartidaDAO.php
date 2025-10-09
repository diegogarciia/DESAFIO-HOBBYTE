<?php
require_once __DIR__ . '/../DataBase/DataBase.php';
require_once __DIR__ . '/../Model/Partida.php';

class PartidaDAO {

    public static function insert($id_usuario, $nombre, $total_casillas) {
        $conexion = Database::connect();
        $sql = "INSERT INTO partida (id_usuario, nombre, estado, total_casillas, casillas_destapadas, casillas_exitosas, perdidas_consecutivas) 
                VALUES (?, ?, 'en_curso', ?, 0, 0, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isi", $id_usuario, $nombre, $total_casillas);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conexion->close();
        return $id;
    }

    public static function getById($id_partida) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM partida WHERE id_partida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $partida = null;

        if ($fila = $resultado->fetch_assoc()) {
            $partida = new Partida(
                $fila['id_partida'],
                $fila['id_usuario'],
                $fila['nombre'],
                $fila['estado'],
                $fila['total_casillas'],
                $fila['casillas_destapadas'],
                $fila['casillas_exitosas'],
                $fila['perdidas_consecutivas'],
                $fila['fecha_creacion']
            );
        }

        $stmt->close();
        $conexion->close();
        return $partida;
    }

    public static function getByUsuario($id_usuario) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM partida WHERE id_usuario = ? AND estado='en_curso'";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $partidas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $partidas[] = new Partida(
                $fila['id_partida'],
                $fila['id_usuario'],
                $fila['nombre'],
                $fila['estado'],
                $fila['total_casillas'],
                $fila['casillas_destapadas'],
                $fila['casillas_exitosas'],
                $fila['perdidas_consecutivas'],
                $fila['fecha_creacion']
            );
        }

        $stmt->close();
        $conexion->close();
        return $partidas;
    }

    public static function update(Partida $partida) {
        $conexion = Database::connect();
        $sql = "UPDATE partida SET nombre=?, estado=?, casillas_destapadas=?, casillas_exitosas=?, perdidas_consecutivas=? WHERE id_partida=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "ssiiii",
            $partida->getNombre(),
            $partida->getEstado(),
            $partida->getCasillasDestapadas(),
            $partida->getCasillasExitosas(),
            $partida->getPerdidasConsecutivas(),
            $partida->getIdPartida()
        );
        $stmt->execute();
        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();
        $conexion->close();
        return $filasAfectadas > 0;
    }
}
