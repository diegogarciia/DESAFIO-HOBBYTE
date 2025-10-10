<?php
require_once __DIR__ . '/../Controller/HeroeController.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$parametros = explode("/", trim($uri, "/"));

$id_heroe = isset($parametros[2]) ? (int)$parametros[2] : 0;

switch ($metodo) {
    case 'GET':
        if ($id_heroe > 0) {
            $heroe = HeroeController::obtenerHeroe($id_heroe);
            echo json_encode($heroe);
        } else {
            $heroes = HeroeController::listarHeroes();
            echo json_encode($heroes);
        }
        break;

    default:
        echo json_encode(["mensaje" => "Método no soportado"]);
}
