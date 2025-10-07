<?php
require_once __DIR__ . '/../Controller/CasillaController.php';
require_once __DIR__ . '/../Helper/Parametros.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$parametros = explode("/", trim($uri, "/"));

$id_partida = isset($parametros[2]) ? (int)$parametros[2] : 0;
$posicion = isset($parametros[3]) ? (int)$parametros[3] : 0;

$input = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {

    case 'GET':
        if ($id_partida > 0) {
            $casillas = CasillaController::listarCasillas($id_partida);
            echo json_encode($casillas);
        } else {
            echo json_encode(["mensaje" => "Debe indicar un ID de partida"]);
        }
        break;

    case 'POST':
        echo json_encode(["mensaje" => "Creación de casilla individual no implementada, use creación de partida"]);
        break;

    case 'PUT':
    case 'PATCH':
        if ($id_partida === 0 || $posicion === 0) {
            echo json_encode(["mensaje" => "Debe indicar ID de partida y posición de casilla"]);
            break;
        }
        $mensaje = CasillaController::destapar($id_partida, $posicion);
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'DELETE':
        echo json_encode(["mensaje" => "Funcionalidad de eliminar casilla no implementada"]);
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
