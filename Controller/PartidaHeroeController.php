<?php
require_once __DIR__ . '/../DataBase/PartidaHeroeDAO.php';
require_once __DIR__ . '/../Model/PartidaHeroe.php';

class PartidaHeroeController {

    public static function agregarHeroe($id_partida, $id_heroe, $poder_inicial = 50, $poder_actual = null, $activo = 1, $derrotado = 0) {
        if ($poder_actual === null) {
            $poder_actual = $poder_inicial; 
        }

        $id = PartidaHeroeDAO::insert($id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado);

        if ($id > 0) {
            return [
                "mensaje" => "Héroe asignado correctamente a la partida",
                "id_partida_heroe" => $id
            ];
        } else {
            return ["error" => "No se pudo asignar el héroe a la partida"];
        }
    }

    public static function obtenerHeroesPorPartida($id_partida) {
        $heroes = PartidaHeroeDAO::getByPartida($id_partida);
        $resultado = [];

        foreach ($heroes as $heroe) {
            $resultado[] = $heroe->toArray();
        }

        return $resultado;
    }

    public static function actualizarPoder($id_partida_heroe, $nuevo_poder) {
        PartidaHeroeDAO::updatePoderActual($id_partida_heroe, $nuevo_poder);
        return ["mensaje" => "Poder del héroe actualizado correctamente"];
    }

    public static function marcarDerrotado($id_partida_heroe) {
        PartidaHeroeDAO::updateDerrotado($id_partida_heroe);
        return ["mensaje" => "El héroe ha sido marcado como derrotado"];
    }
}
?>
