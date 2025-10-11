<?php
require_once __DIR__ . '/../Controller/PartidaHeroeController.php';
require_once __DIR__ . '/../Helper/Parametros.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$parametros = explode("/", trim($uri, "/"));

$id_partida = isset($parametros[2]) ? (int)$parametros[2] : 0;
$id_partida_heroe = isset($parametros[3]) ? (int)$parametros[3] : 0;

$input = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {

    case 'GET':
        if ($id_partida > 0) {
            $heroes = PartidaHeroeController::obtenerHeroesPorPartida($id_partida);
            echo json_encode($heroes);
        } else {
            echo json_encode(["mensaje" => "Debe indicar el ID de la partida"]);
        }
        break;

    case 'POST':
        $id_heroe = $input['id_heroe'] ?? 0;

        if ($id_partida <= 0 || $id_heroe <= 0) {
            echo json_encode(["error" => "Debe indicar el ID de la partida y del héroe"]);
            break;
        }

        $poder_inicial = 50;
        $poder_actual = 50;
        $activo = 1;
        $derrotado = 0;

        $resultado = PartidaHeroeController::agregarHeroe($id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado);
        echo json_encode($resultado);
        break;

    case 'PUT':
    case 'PATCH':
        if ($id_partida_heroe <= 0 || !isset($input['nuevo_poder'])) {
            echo json_encode(["error" => "Debe indicar el ID del héroe en partida y el nuevo poder"]);
            break;
        }

        $nuevo_poder = $input['nuevo_poder'];
        $mensaje = PartidaHeroeController::actualizarPoder($id_partida_heroe, $nuevo_poder);
        echo json_encode($mensaje);
        break;

    case 'DELETE':
        if ($id_partida_heroe <= 0) {
            echo json_encode(["error" => "Debe indicar el ID del héroe en partida para marcarlo como derrotado"]);
            break;
        }

        $mensaje = PartidaHeroeController::marcarDerrotado($id_partida_heroe);
        echo json_encode($mensaje);
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
?>
