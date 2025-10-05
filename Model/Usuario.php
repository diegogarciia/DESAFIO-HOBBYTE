<?php

class Usuario {
    private $id;
    private $nombre;
    private $correo;
    private $contrasena;
    private $rol;

    public function __construct($id = null, $nombre = null, $correo = null, $contrasena = null, $rol = 'jugador') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getContrasena() { return $this->contrasena; }
    public function getRol() { return $this->rol; }

    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setContrasena($contrasena) { $this->contrasena = $contrasena; }
    public function setRol($rol) { $this->rol = $rol; }
}
