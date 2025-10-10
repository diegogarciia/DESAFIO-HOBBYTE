<?php
require_once __DIR__ . '/../DataBase/Database.php';
require_once __DIR__ . '/../Model/Heroe.php';

class HeroeDAO {

    public static function getAll() {
        $conexion = Database::connect();
        $sql = "SELECT * FROM heroe";
        $resultado = $conexion->query($sql);
        $heroes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $heroes[] = new Heroe(
                $fila['id_heroe'],
                $fila['nombre'],
                $fila['habilidad'],
                $fila['poder']
            );
        }
        $conexion->close();
        return $heroes;
    }

    public static function getById($id_heroe) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM heroe WHERE id_heroe = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_heroe);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $heroe = null;
        if ($fila = $resultado->fetch_assoc()) {
            $heroe = new Heroe(
                $fila['id_heroe'],
                $fila['nombre'],
                $fila['habilidad'],
                $fila['poder']
            );
        }
        $stmt->close();
        $conexion->close();
        return $heroe;
    }
}
