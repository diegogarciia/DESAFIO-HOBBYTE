<?php
require_once __DIR__ . '/../DataBase/HeroeDAO.php';

class HeroeController {

    public static function listarHeroes() {
        $heroes = HeroeDAO::getAll();
        $heroesArray = [];
        foreach ($heroes as $heroe) {
            $heroesArray[] = $heroe->toArray();
        }
        return $heroesArray;
    }

    public static function obtenerHeroe($id_heroe) {
        $heroe = HeroeDAO::getById($id_heroe);
        return $heroe ? $heroe->toArray() : null;
    }

}
