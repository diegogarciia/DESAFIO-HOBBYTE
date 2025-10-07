<?php
require_once __DIR__ . '/../DataBase/CasillaDAO.php';
require_once __DIR__ . '/../Model/Casilla.php';

class CasillaController {

    public static function listarCasillas($id_partida) {
        $casillas_obj = CasillaDAO::getByPartida($id_partida);
        if (empty($casillas_obj)) return "No se encontraron casillas para esta partida.";
        
        $casillas_array = [];
        foreach ($casillas_obj as $casilla) {
            $casillas_array[] = $casilla->toArray();
        }
        
        return $casillas_array;
    }

    public static function destapar($id_partida, $posicion) {
        $casilla = CasillaDAO::getByPosicion($id_partida, $posicion);
        if (!$casilla) return "Casilla no encontrada.";

        $exito = rand(0,1);
        $casilla->setDestapada(1);
        $casilla->setExito($exito);

        CasillaDAO::updateDestapadaExito($casilla->getIdCasilla(), 1, $exito);

        return $exito ? "Casilla destapada con éxito." : "Casilla destapada pero prueba fallida.";
    }
}
