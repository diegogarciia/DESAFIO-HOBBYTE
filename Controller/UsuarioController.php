<?php
require_once __DIR__ . '/../DataBase/UsuarioDAO.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioController {

    // Crear un nuevo usuario
    public static function crearUsuario($nombre, $correo, $contrasena) {
        // Validar datos básicos
        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            return "Todos los campos son obligatorios.";
        }

        // Comprobar si el correo ya existe
        $usuarioExistente = UsuarioDAO::getByCorreo($correo);
        if ($usuarioExistente) {
            return "El correo ya esta registrado.";
        }

        // Insertar usuario
        $id = UsuarioDAO::insert($nombre, $correo, $contrasena);
        return "Usuario creado correctamente";
    }

    // Actualizar un usuario existente
    public static function actualizarUsuario(Usuario $usuario) {
        $exito = UsuarioDAO::update($usuario);
        return $exito ? "Usuario actualizado correctamente." : "No se pudo actualizar el usuario.";
    }

    // Borrar un usuario por ID
    public static function eliminarUsuario($id) {
        $exito = UsuarioDAO::delete($id);
        return $exito ? "Usuario eliminado correctamente." : "No se pudo eliminar el usuario.";
    }

    // Obtener todos los usuarios
    public static function listarUsuarios() {
        return UsuarioDAO::getAll();
    }

    // Obtener usuario por ID
    public static function obtenerUsuario($id) {
        return UsuarioDAO::getById($id);
    }
}
