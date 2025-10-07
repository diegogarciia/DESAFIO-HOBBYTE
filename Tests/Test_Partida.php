<?php
require_once __DIR__ . '/../Controller/PartidaController.php';
require_once __DIR__ . '/../Helper/Parametros.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$parametros = explode("/", trim($uri, "/"));

$id_partida = isset($parametros[2]) ? (int)$parametros[2] : 0;
$id_usuario = isset($parametros[3]) ? (int)$parametros[3] : 0;

$input = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {

    case 'GET':
        if ($id_partida > 0) {
            $partida = PartidaController::obtenerPartida($id_partida);
            echo json_encode($partida);
        } elseif ($id_usuario > 0) {
            $partidas = PartidaController::listarPartidasUsuario($id_usuario);
            echo json_encode($partidas);
        } else {
            echo json_encode(["mensaje" => "Debe indicar un ID de partida o usuario"]);
        }
        break;

    case 'POST':
        $nombre = $input['nombre'] ?? 'PartidaDefault';
        $total_casillas = $input['total_casillas'] ?? 20;
        $mensaje = PartidaController::crearPartida($id_usuario, $nombre, $total_casillas);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'PUT':
    case 'PATCH':
        if ($id_partida === 0 || !isset($input['posicion'])) {
            echo json_encode(["mensaje" => "Debe indicar ID de partida y posición de casilla a actualizar"]);
            break;
        }
        $posicion = $input['posicion'];
        $mensaje = PartidaController::destaparCasilla($id_partida, $posicion);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'DELETE':
        echo json_encode(["mensaje" => "Funcionalidad de eliminar no implementada"]);
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
