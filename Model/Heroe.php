<?php 

class Heroe {
    private $id_heroe;
    private $nombre;
    private $habilidad;
    private $poder;

    public function __construct($id_heroe,$nombre,$habilidad,$poder) {
        $this->id_heroe = $id_heroe;
        $this->nombre = $nombre;
        $this->habilidad = $habilidad;
        $this->poder = $poder;
    }

    public function getIdHeroe() { return $this->id_heroe; }
    public function getNombre() { return $this->nombre; }
    public function getHabilidad() { return $this->habilidad; }
    public function getPoder() { return $this->poder; }

    public function setIdHeroe($id_heroe) { $this->id_heroe = $id_heroe; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setHabilidad($habilidad) { $this->habilidad = $habilidad; }
    public function setPoder($poder) { $this->poder = $poder; }

    public function toArray() {
        return [
            'id_heroe' => $this->id_heroe,
            'nombre' => $this->nombre,
            'habilidad' => $this->habilidad,
            'poder' => $this->poder
        ];
    }

}