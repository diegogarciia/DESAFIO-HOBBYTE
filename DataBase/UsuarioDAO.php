<?php
require_once __DIR__ . '/../DataBase/DataBase.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioDAO {

    public static function insert($nombre, $correo, $contrasena) {
        $conexion = Database::connect();
        $sql = "INSERT INTO usuario (nombre, correo, contrasena) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $correo, $contrasena);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conexion->close();
        return $id;
    }

    public static function getByCorreo($correo) {
        $conexion = Database::connect();
        $sql = "SELECT * FROM usuario WHERE correo = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = null;

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = new Usuario(
                $fila["nombre"],
                $fila["correo"],
                $fila["rol"]
            );
        }

        $stmt->close();
        $conexion->close();
        return $usuario;
    }

    public static function getById($id) {
        $conexion = Database::connect();
        $sql = "SELECT nombre, correo, rol FROM usuario WHERE id_jugador = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = null;
        
        if ($fila = $resultado->fetch_assoc()) {
            $usuario = [
                'nombre' => $fila['nombre'],
                'correo' => $fila['correo'],
                'rol' => $fila['rol']
            ];
        }
        
        $stmt->close();
        $conexion->close();
        return $usuario;
    }

    public static function getAll() {
    $conexion = Database::connect();
    $sql = "SELECT nombre, correo, rol FROM usuario";
    $resultado = $conexion->query($sql);
        
    $usuarios = [];
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila; 
            }
            $resultado->free();
        }
            
        $conexion->close();
        return $usuarios;
    }

    public static function delete($id) {
    $conexion = Database::connect();
    $sql = "DELETE FROM usuario WHERE id_jugador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $filas_afectadas = $stmt->affected_rows; 

    $stmt->close();
    $conexion->close();

    return $filas_afectadas > 0; 
    }



    public static function update(Usuario $usuario) {
    $conexion = Database::connect();
    $sql = "UPDATE usuario SET nombre = ?, correo = ?, contrasena = ? WHERE id_jugador = ?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }

    $nombre = $usuario->getNombre();
    $correo = $usuario->getCorreo();
    $contrasena = $usuario->getContrasena();
    $id = $usuario->getId();

    $stmt->bind_param("sssi", $nombre, $correo, $contrasena, $id);
    $stmt->execute();

    $filasAfectadas = $stmt->affected_rows;

    $stmt->close();
    $conexion->close();

    return $filasAfectadas > 0;
}
}