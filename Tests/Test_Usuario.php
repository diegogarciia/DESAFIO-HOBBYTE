<?php
require_once __DIR__ . '/../Helper/Parametros.php';
require_once __DIR__ . '/../Controller/UsuarioController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];               
$parametros = explode("/", trim($uri, "/"));  

$id = isset($parametros[2]) ? (int)$parametros[2] : 0;

$input = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {

    case 'GET':
        if ($id > 0) {
            $usuario = UsuarioController::obtenerUsuario($id);
            echo json_encode($usuario);
        } else {
            $usuarios = UsuarioController::listarUsuarios();
            echo json_encode($usuarios);
        }
        break;

    case 'POST': 
        $nombre = $input['nombre'] ?? 'UsuarioDefault'; 
        $correo = $input['correo'] ?? 'default@gmail.com'; 
        $contrasena = $input['contrasena'] ?? '1234'; 

        $mensaje = UsuarioController::crearUsuario($nombre, $correo, $contrasena); 
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'PUT': 
    case 'PATCH':
        if ($id === 0) {
            echo json_encode(["mensaje" => "Debe indicar un ID en la URL para actualizar"]);
            break;
        }

        $nombre = $input['nombre'] ?? 'UsuarioDefault';
        $correo = $input['correo'] ?? 'default@gmail.com';
        $contrasena = $input['contrasena'] ?? '1234';
        $rol = $input['rol'] ?? 'jugador';

        $usuario = new Usuario($id, $nombre, $correo, $contrasena, $rol);
        $mensaje = UsuarioController::actualizarUsuario($usuario);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'DELETE':
        if ($id === 0) {
            echo json_encode(["mensaje" => "Debe indicar un ID en la URL para eliminar"]);
            break;
        }
        $mensaje = UsuarioController::eliminarUsuario($id);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
