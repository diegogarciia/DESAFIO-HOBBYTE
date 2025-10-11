<?php
class PartidaHeroe {
    private $id_partida_heroe;
    private $id_partida;
    private $id_heroe;
    private $poder_inicial;
    private $poder_actual;
    private $activo;
    private $derrotado;

    public function __construct($id_partida_heroe, $id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado) {
        $this->id_partida_heroe = $id_partida_heroe;
        $this->id_partida = $id_partida;
        $this->id_heroe = $id_heroe;
        $this->poder_inicial = $poder_inicial;
        $this->poder_actual = $poder_actual;
        $this->activo = $activo;
        $this->derrotado = $derrotado;
    }

    public function getIdPartidaHeroe() { return $this->id_partida_heroe; }
    public function getIdPartida() { return $this->id_partida; }
    public function getIdHeroe() { return $this->id_heroe; }
    public function getPoderInicial() { return $this->poder_inicial; }
    public function getPoderActual() { return $this->poder_actual; }
    public function getActivo() { return $this->activo; }
    public function getDerrotado() { return $this->derrotado; }

    public function setIdPartidaHeroe($id_partida_heroe) { $this->id_partida_heroe = $id_partida_heroe; }
    public function setIdPartida($id_partida) { $this->id_partida = $id_partida; }
    public function setIdHeroe($id_heroe) { $this->id_heroe = $id_heroe; }
    public function setPoderInicial($poder_inicial) { $this->poder_inicial = $poder_inicial; }
    public function setPoderActual($poder_actual) { $this->poder_actual = $poder_actual; }
    public function setActivo($activo) { $this->activo = $activo; }
    public function setDerrotado($derrotado) { $this->derrotado = $derrotado; }

    public function toArray() {
    return [
        "id_partida_heroe" => $this->id_partida_heroe,
        "id_partida" => $this->id_partida,
        "id_heroe" => $this->id_heroe,
        "poder_inicial" => $this->poder_inicial,
        "poder_actual" => $this->poder_actual,
        "activo" => $this->activo,
        "derrotado" => $this->derrotado
    ];
}

}
?>
