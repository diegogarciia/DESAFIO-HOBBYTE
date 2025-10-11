<?php
require_once __DIR__ . '/../Controller/UsuarioController.php';
require_once __DIR__ . '/../Controller/HeroeController.php';
require_once __DIR__ . '/../Controller/PartidaController.php';
require_once __DIR__ . '/../Controller/PartidaHeroeController.php';
require_once __DIR__ . '/../Controller/CasillaController.php';

header("Content-Type: application/json; charset=UTF-8");

$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$accion = $uri[count($uri) - 1];

$input = json_decode(file_get_contents("php://input"), true) ?? [];

try {
    switch ($accion) {

        case "crearUsuario":
            $nombre = $input['nombre'] ?? 'UsuarioDefault'; 
            $correo = $input['correo'] ?? 'default@gmail.com'; 
            $contrasena = $input['contrasena'] ?? '1234'; 
            
            $mensaje = UsuarioController::crearUsuario($nombre, $correo, $contrasena); 
            echo json_encode(["mensaje" => $mensaje]);
            
            break;
            
        case "listarUsuarios":
            $usuarios = UsuarioController::listarUsuarios();
            echo json_encode($usuarios);
            
            break;

        case "crearPartida":
            $id_usuario = $input['id_usuario'] ?? 1;
            $nombre = $input['nombre'] ?? 'PartidaDefault';
            $total_casillas = $input['total_casillas'] ?? 20;
            $mensaje = PartidaController::crearPartida($id_usuario, $nombre, $total_casillas);
            echo json_encode(["mensaje" => $mensaje]);
            
            break;

        case "listarPartidas":
            $id_usuario = $input['id_usuario'] ?? 1;
            $partidas = PartidaController::listarPartidasUsuario($id_usuario);
            echo json_encode($partidas);
            
            break;

        case "listarHeroes":
            $heroes = HeroeController::listarHeroes();
            echo json_encode($heroes);
            
            break;

        case "asignarHeroe":
            $id_partida = $input['id_partida'] ?? 1;
            $id_heroe = $input['id_heroe'] ?? 0;
            
            if ($id_partida <= 0 || $id_heroe <= 0) {
                echo json_encode(["error" => "Debe indicar el ID de la partida y del héroe"]);
                break;
            }
            
            $poder_inicial = 50;
            $poder_actual = 50;
            $activo = 1;
            $derrotado = 0;
            
            $resultado = PartidaHeroeController::agregarHeroe($id_partida, $id_heroe, $poder_inicial, $poder_actual, $activo, $derrotado);
            echo json_encode($resultado);
            break;

        case "verHeroesPartida":
            $id_partida = $input['id_partida'] ?? 0;
            if ($id_partida > 0) {
                $heroes = PartidaHeroeController::obtenerHeroesPorPartida($id_partida);
                echo json_encode($heroes);
            } else {
                echo json_encode(["mensaje" => "Debe indicar el ID de la partida"]);
            }
            
            break;

        case "simularPartida":
            
            $id_partida = $input['id_partida'] ?? 0;
            
            if ($id_partida <= 0) {
                echo json_encode(["error" => "Debe indicar el ID de la partida para simular."]);
                break;
            }
            
            $partida = PartidaController::obtenerPartida($id_partida);
            
            if (!$partida || (is_array($partida) && isset($partida["mensaje"]))) {
                echo json_encode(["error" => "Partida no encontrada."]);
                break;
            }
            
            $heroesPartida = PartidaHeroeController::obtenerHeroesPorPartida($id_partida);
            
            if (empty($heroesPartida)) {
                echo json_encode(["error" => "No hay héroes asignados a esta partida."]);
                break;
            }
            
            $heroe = $heroesPartida[0]; 
            $poderActual = $heroe["poder_actual"];
            $fallosConsecutivos = 0;
            $exitos = 0;
            
            $casillas = CasillaController::listarCasillas($id_partida);
            
            if (!is_array($casillas)) {
                echo json_encode(["error" => "No se pudieron obtener las casillas de la partida."]);
                break;
            }
            
            foreach ($casillas as $casilla) {
                
                if ($fallosConsecutivos >= 5) break; 
                
                $habilidad = $casilla["habilidad"];
                $esfuerzo = $casilla["esfuerzo_requerido"];
                
                if ($poderActual >= $esfuerzo) {
                    $poderActual -= $esfuerzo;
                    $exitos++;
                    $fallosConsecutivos = 0;
                    
                    
                PartidaHeroeController::actualizarPoder($heroe["id_partida_heroe"], $poderActual);
                CasillaController::destapar($id_partida, $casilla["posicion"]);
            
            } else {
                $fallosConsecutivos++;
            }
        }
        
        if ($fallosConsecutivos >= 5) {
            $partida->setEstado("perdida");
            PartidaDAO::update($partida);
            $resultado = "El héroe ha sido derrotado tras 5 fallos consecutivos.";
        } elseif ($exitos === count($casillas)) {
            $partida->setEstado("ganada");
            PartidaDAO::update($partida);
            $resultado = "¡Partida completada con éxito! El héroe superó todas las pruebas.";
        } else {
            $resultado = "La partida finalizó antes de completar todas las casillas.";
        }
        
        echo json_encode([
            "id_partida" => $id_partida,
            "heroe" => $heroe["id_heroe"],
            "resultado" => $resultado,
            "poder_final" => $poderActual,
            "casillas_superadas" => $exitos,
            "fallos_consecutivos" => $fallosConsecutivos
        ]);
        break;
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}