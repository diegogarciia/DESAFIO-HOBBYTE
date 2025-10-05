<?php
require_once __DIR__ . '/../DataBase/UsuarioDAO.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioController {

    public static function crearUsuario($nombre, $correo, $contrasena) {
        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            return "Todos los campos son obligatorios.";
        }

        $usuarioExistente = UsuarioDAO::getByCorreo($correo);
        if ($usuarioExistente) {
            return "El correo ya esta registrado.";
        }

        $id = UsuarioDAO::insert($nombre, $correo, $contrasena);
        return "Usuario creado correctamente";
    }

    public static function actualizarUsuario(Usuario $usuario) {
        $exito = UsuarioDAO::update($usuario);
        return $exito ? "Usuario actualizado correctamente." : "No se pudo actualizar el usuario.";
    }

    public static function eliminarUsuario($id) {
        $exito = UsuarioDAO::delete($id);
        return $exito ? "Usuario eliminado correctamente." : "No se pudo eliminar el usuario.";
    }

    public static function listarUsuarios() {
        return UsuarioDAO::getAll();
    }

    public static function obtenerUsuario($id) {
        return UsuarioDAO::getById($id);
    }
}
