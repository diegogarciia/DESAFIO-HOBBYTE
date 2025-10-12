<?php
require_once __DIR__ . '/../DataBase/UsuarioDAO.php';
require_once __DIR__ . '/../Model/Usuario.php';

class UsuarioController {

    // Crear un nuevo usuario
    public static function crearUsuario($nombre, $correo, $contrasena, $rol) {
        // Validar datos básicos
        if (empty($nombre) || empty($correo) || empty($contrasena) || empty($rol)) {
            return "Todos los campos son obligatorios.";
        }

        // Comprobar si el correo ya existe
        $usuarioExistente = UsuarioDAO::getByCorreo($correo);
        if ($usuarioExistente) {
            return "El correo ya esta registrado.";
        }

        // Insertar usuario
        $id = UsuarioDAO::insert($nombre, $correo, $contrasena, $rol);
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

    // Iniciar sesión (login)
public static function login($correo, $contrasena) {
    if (empty($correo) || empty($contrasena)) {
        return ["error" => "Debe indicar correo y contraseña."];
    }

    $usuario = UsuarioDAO::verificarCredenciales($correo, $contrasena);
    if (!$usuario) {
        return ["error" => "Credenciales incorrectas."];
    }

    $mensaje = ($usuario["rol"] === "admin")
        ? "Inicio de sesión como administrador."
        : "Inicio de sesión como jugador.";

    return [
        "id_usuario" => $usuario["id_jugador"],
        "nombre" => $usuario["nombre"],
        "rol" => $usuario["rol"],
        "mensaje" => $mensaje
    ];
}

public static function cambiarContrasena($correoUsuario, $nuevaContrasena, $rolActual) {
    if ($rolActual !== "admin") {
        return ["error" => "No tiene permisos para cambiar contraseñas."];
    }

    $exito = UsuarioDAO::cambiarContrasena($correoUsuario, $nuevaContrasena);
    return $exito
        ? ["mensaje" => "Contraseña actualizada correctamente."]
        : ["error" => "No se pudo actualizar la contraseña."];
}


    // Obtener usuario por ID
    public static function obtenerUsuario($id) {
        return UsuarioDAO::getById($id);
    }
}
