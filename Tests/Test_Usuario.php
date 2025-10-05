<?php
require_once __DIR__ . '/../Helper/Parametros.php';
require_once __DIR__ . '/../Controller/UsuarioController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);

$id = $_GET['id'] ?? 0;

switch ($metodo) {

    case 'GET':
        if ($id) {
            $usuario = UsuarioController::obtenerUsuario($id);
            echo json_encode($usuario);
        } else {
            $usuarios = UsuarioController::listarUsuarios();
            echo json_encode($usuarios);
        }
        break;

    case 'POST': 
      $input = json_decode(file_get_contents('php://input'), true); 

      $nombre = $input['nombre'] ?? 'UsuarioDefault'; 
      $correo = $input['correo'] ?? 'default@gmail.com'; 
      $contrasena = $input['contrasena'] ?? '1234'; 

      $mensaje = UsuarioController::crearUsuario($nombre, $correo, $contrasena); 

      echo json_encode(["mensaje" => $mensaje]);
    break;

    case 'PUT': 
    case 'PATCH':
        $id = $input['id_jugador'] ?? 0;
        $nombre = $input['nombre'] ?? 'UsuarioDefault';
        $correo = $input['correo'] ?? 'default@gmail.com';
        $contrasena = $input['contrasena'] ?? '1234';
        $rol = $input['rol'] ?? 'jugador';

        $usuario = new Usuario($id, $nombre, $correo, $contrasena, $rol);
        $mensaje = UsuarioController::actualizarUsuario($usuario);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'DELETE':
        if (!$id) {
            echo json_encode(["mensaje" => "Debe proporcionar un ID para eliminar"]);
            break;
        }
        $mensaje = UsuarioController::eliminarUsuario($id);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
