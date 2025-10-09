<?php
class Casilla {
    private $id_casilla;
    private $id_partida;
    private $posicion;
    private $habilidad;
    private $esfuerzo_requerido;
    private $destapada;
    private $exito;

    public function __construct($id_casilla, $id_partida, $posicion, $habilidad, $esfuerzo_requerido, $destapada, $exito) {
        $this->id_casilla = $id_casilla;
        $this->id_partida = $id_partida;
        $this->posicion = $posicion;
        $this->habilidad = $habilidad;
        $this->esfuerzo_requerido = $esfuerzo_requerido;
        $this->destapada = $destapada;
        $this->exito = $exito;
    }

    public function getIdCasilla() { return $this->id_casilla; }
    public function getIdPartida() { return $this->id_partida; }
    public function getPosicion() { return $this->posicion; }
    public function getHabilidad() { return $this->habilidad; }
    public function getEsfuerzoRequerido() { return $this->esfuerzo_requerido; }
    public function getDestapada() { return $this->destapada; }
    public function getExito() { return $this->exito; }

    public function setIdCasilla($id_casilla) { $this->id_casilla = $id_casilla; }
    public function setIdPartida($id_partida) { $this->id_partida = $id_partida; }
    public function setPosicion($posicion) { $this->posicion = $posicion; }
    public function setHabilidad($habilidad) { $this->habilidad = $habilidad; }
    public function setEsfuerzoRequerido($esfuerzo_requerido) { $this->esfuerzo_requerido = $esfuerzo_requerido; }
    public function setDestapada($destapada) { $this->destapada = $destapada; }
    public function setExito($exito) { $this->exito = $exito; }

    public function toArray() {
    return [
        'id_casilla' => $this->id_casilla,
        'id_partida' => $this->id_partida,
        'posicion' => $this->posicion,
        'habilidad' => $this->habilidad,
        'esfuerzo_requerido' => $this->esfuerzo_requerido,
        'destapada' => $this->destapada,
        'exito' => $this->exito
    ];
}

}
