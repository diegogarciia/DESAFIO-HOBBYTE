<?php
require_once __DIR__ . '/../DataBase/PartidaDAO.php';
require_once __DIR__ . '/../DataBase/CasillaDAO.php';
require_once __DIR__ . '/../Model/Partida.php';
require_once __DIR__ . '/../Model/Casilla.php';

class PartidaController {

    public static function crearPartida($id_usuario, $nombre, $total_casillas) {
        if (empty($id_usuario) || empty($nombre) || $total_casillas <= 0) {
            return "Datos de partida inválidos.";
        }

        $id_partida = PartidaDAO::insert($id_usuario, $nombre, $total_casillas);

        $habilidades = ['magia', 'fuerza', 'habilidad'];
        $esfuerzos = array_merge(
            array_fill(0, round($total_casillas*0.65), rand(5,20)),
            array_fill(0, round($total_casillas*0.30), rand(25,40)),
            array_fill(0, round($total_casillas*0.05), rand(45,50))
        );
        shuffle($esfuerzos);

        for ($i=0; $i<$total_casillas; $i++) {
            $habilidad = $habilidades[array_rand($habilidades)];
            $esfuerzo = $esfuerzos[$i];
            CasillaDAO::insert($id_partida, $i, $habilidad, $esfuerzo);
        }

        return "Partida creada correctamente";
    }

    public static function destaparCasilla($id_partida, $posicion) {
        $casillas = CasillaDAO::getByPartida($id_partida);
        $casilla = null;

        foreach ($casillas as $c) {
            if ($c->getPosicion() == $posicion) {
                $casilla = $c;
                break;
            }
        }

        if (!$casilla) return "Casilla no encontrada";

        if ($casilla->getDestapada()) return "Casilla ya destapada";

        // Simulación de resultado de prueba
        $exito = rand(0,1); // 0=falla, 1=éxito

        // Actualizamos la casilla
        CasillaDAO::updateDestapadaExito($casilla->getIdCasilla(), 1, $exito);

        // Actualizamos la partida
        $partida = PartidaDAO::getById($id_partida);
        $partida->setCasillasDestapadas($partida->getCasillasDestapadas() + 1);
        if ($exito) {
            $partida->setCasillasExitosas($partida->getCasillasExitosas() + 1);
        } else {
            $partida->setPerdidasConsecutivas($partida->getPerdidasConsecutivas() + 1);
        }

        PartidaDAO::update($partida);

        if($exito == 0) {
            return "Casilla destapada pero prueba sin éxito";
        } else {
            return "Casilla destapada y prueba realizada con éxito";            
        }

    }
}
