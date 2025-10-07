<?php
class Partida {
    public $id_partida;
    public $id_usuario;
    public $nombre;
    public $estado;
    public $total_casillas;
    public $casillas_destapadas;
    public $casillas_exitosas;
    public $perdidas_consecutivas;
    public $fecha_creacion;

    public function __construct($id_partida, $id_usuario, $nombre, $estado, $total_casillas, $casillas_destapadas, $casillas_exitosas, $perdidas_consecutivas, $fecha_creacion) {
        $this->id_partida = $id_partida;
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->estado = $estado;
        $this->total_casillas = $total_casillas;
        $this->casillas_destapadas = $casillas_destapadas;
        $this->casillas_exitosas = $casillas_exitosas;
        $this->perdidas_consecutivas = $perdidas_consecutivas;
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getIdPartida() { return $this->id_partida; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getNombre() { return $this->nombre; }
    public function getEstado() { return $this->estado; }
    public function getTotalCasillas() { return $this->total_casillas; }
    public function getCasillasDestapadas() { return $this->casillas_destapadas; }
    public function getCasillasExitosas() { return $this->casillas_exitosas; }
    public function getPerdidasConsecutivas() { return $this->perdidas_consecutivas; }
    public function getFechaCreacion() { return $this->fecha_creacion; }

    public function setIdPartida($id_partida) { $this->id_partida = $id_partida; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setTotalCasillas($total_casillas) { $this->total_casillas = $total_casillas; }
    public function setCasillasDestapadas($casillas_destapadas) { $this->casillas_destapadas = $casillas_destapadas; }
    public function setCasillasExitosas($casillas_exitosas) { $this->casillas_exitosas = $casillas_exitosas; }
    public function setPerdidasConsecutivas($perdidas_consecutivas) { $this->perdidas_consecutivas = $perdidas_consecutivas; }
    public function setFechaCreacion($fecha_creacion) { $this->fecha_creacion = $fecha_creacion; }

}
