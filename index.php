<?php

require_once __DIR__ . '/Controller/UsuarioController.php';
require_once __DIR__ . '/Controller/HeroeController.php';
require_once __DIR__ . '/Controller/PartidaController.php';
require_once __DIR__ . '/Controller/PartidaHeroeController.php';
require_once __DIR__ . '/Controller/CasillaController.php';

header("Content-Type: application/json; charset=UTF-8");

$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

if ($uri[0] === 'index.php') {
    array_shift($uri);
}

$recurso = $uri[0] ?? '';
$subaccion = $uri[1] ?? '';

$input = json_decode(file_get_contents("php://input"), true) ?? [];

try {
    switch ($recurso) {

        case "login":
            $correo = $input["correo"] ?? "";
            $contrasena = $input["contrasena"] ?? "";

            if (empty($correo) || empty($contrasena)) {
                echo json_encode(["error" => "Debe proporcionar correo y contraseña."]);
                break;
            }

            $usuario = UsuarioController::login($correo, $contrasena);

            if (!$usuario || isset($usuario["error"])) {
                echo json_encode(["error" => "Credenciales incorrectas."]);
                break;
            }

            echo json_encode([
                "id_usuario" => $usuario["id_usuario"],
                "nombre" => $usuario["nombre"],
                "rol" => $usuario["rol"],
                "mensaje" => $usuario["rol"] === "admin"
                    ? "Inicio de sesión como administrador."
                    : "Inicio de sesión como jugador."
            ]);
            break;
        
        case "admin":
            if (!$subaccion) {
                echo json_encode(["error" => "Debe indicar una acción dentro de /admin (por ejemplo, /admin/cambiarContrasena)."]);
                break;
            }

            switch ($subaccion) {

                case "cambiarContrasena":
                    $emailAdmin = $input["email_admin"] ?? "";
                    $passwordAdmin = $input["password_admin"] ?? "";
                    $emailObjetivo = $input["email_objetivo"] ?? "";
                    $nuevaPassword = $input["nueva_password"] ?? "";
                    
                    if (!$emailAdmin || !$passwordAdmin || !$emailObjetivo || !$nuevaPassword) {
                        echo json_encode(["error" => "Faltan datos."]);
                        break;
                    }
                    
                    $admin = UsuarioController::login($emailAdmin, $passwordAdmin);
                    
                    if (!$admin || isset($admin["error"])) {
                        echo json_encode(["error" => "Credenciales de administrador incorrectas."]);
                        break;
                    }
                    
                    if ($admin["rol"] !== "admin") {
                        echo json_encode(["error" => "Acceso denegado. Solo los administradores pueden realizar esta acción."]);
                        break;
                    }
                    
                    $resultado = UsuarioController::cambiarContrasena($emailObjetivo, $nuevaPassword, $admin["rol"]);
                    echo json_encode($resultado);
                    break;

                case "crearUsuario":
                    $nombre = $input["nombre"] ?? "";
                    $correo = $input["correo"] ?? "";
                    $contrasena = $input["contrasena"] ?? "";
                    $rol = $input["rol"] ?? "jugador";

                    if (!$nombre || !$correo || !$contrasena) {
                        echo json_encode(["error" => "Faltan datos para crear el usuario."]);
                        break;
                    }

                    $resultado = UsuarioController::crearUsuario($nombre, $correo, $contrasena, $rol);
                    echo json_encode($resultado);
                    break;

                default:
                    echo json_encode(["error" => "Acción de administrador no reconocida."]);
                    break;
            }
            break;

        case "jugador":
            if (!$subaccion) {
                echo json_encode(["error" => "Debe indicar una acción dentro de /jugador."]);
                break;
            }

            switch ($subaccion) {

                case "listar":
                    $jugadores = UsuarioController::listarUsuarios();
                    echo json_encode($jugadores);
                    break;

                default:
                    echo json_encode(["error" => "Acción de jugador no reconocida."]);
                    break;
            }
            break;

        case "crearUsuario":
            $nombre = $input['nombre'] ?? 'UsuarioDefault'; 
            $correo = $input['correo'] ?? 'default@gmail.com'; 
            $contrasena = $input['contrasena'] ?? '1234';
            $rol = $input['rol'] ?? 'jugador'; 
            
            $mensaje = UsuarioController::crearUsuario($nombre, $correo, $contrasena, $rol); 
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
            $verificacion = UsuarioController::puedeCrearPartida($id_usuario);
            if (isset($verificacion["error"])) {
                echo json_encode($verificacion);
                break;
            }
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
            
            if (!$partida) {
                echo json_encode(["error" => "Partida no encontrada."]);
                break;
            }
            
            $heroesPartida = PartidaHeroeController::obtenerHeroesPorPartida($id_partida);
            
            if (empty($heroesPartida)) {
                echo json_encode(["error" => "No hay héroes asignados a esta partida."]);
                break;
            }
            
            $casillas = CasillaController::listarCasillas($id_partida);
            
            if (!is_array($casillas)) {
                echo json_encode(["error" => "No se pudieron obtener las casillas de la partida."]);
                break;
            }
            
            $fallosConsecutivos = 0;
            $casillasDestapadas = 0;
            $casillasExitosas = 0;
            $resultado = "";

            foreach ($casillas as $casilla) {
                if ($fallosConsecutivos >= 5) break;
                
                $habilidadCasilla = $casilla["habilidad"];
                $esfuerzo = $casilla["esfuerzo_requerido"];
                $casillaSuperada = false;
                
                foreach ($heroesPartida as &$heroe) {
                    if ($heroe["derrotado"]) continue;
                    
                    $heroeInfo = HeroeController::obtenerHeroe($heroe["id_heroe"]);
                    
                    if (!$heroeInfo) continue;
                    
                    $habilidadHeroe = $heroeInfo["habilidad"];
                    $poderActual = $heroe["poder_actual"];
                    
                    if ($habilidadHeroe == $habilidadCasilla) {
                        if ($poderActual >= $esfuerzo) {
                            $poderActual -= $esfuerzo;
                            $heroe["poder_actual"] = $poderActual;
                            $casillasExitosas++;
                            $casillaSuperada = true;
                            $fallosConsecutivos = 0;
                            
                            PartidaHeroeController::actualizarPoder($heroe["id_partida_heroe"], $poderActual);
                            CasillaController::destapar($id_partida, $casilla["posicion"], true);
                        } else {
                            $fallosConsecutivos++;
                            CasillaController::destapar($id_partida, $casilla["posicion"], false);
                            
                            if ($poderActual <= 0) {
                                PartidaHeroeController::marcarDerrotado($heroe["id_partida_heroe"]);
                                $heroe["derrotado"] = 1;
                            }
                        }
                        $casillasDestapadas++;
                        break; 
                    }
                }
                
                if (!$casillaSuperada && $fallosConsecutivos < 5) continue;
            }
            
            $partida->setCasillasDestapadas($casillasDestapadas);
            $partida->setCasillasExitosas($casillasExitosas);
            $partida->setPerdidasConsecutivas($fallosConsecutivos);
            
            if ($fallosConsecutivos >= 5) {
                $partida->setEstado("perdida");
                $resultado = "La partida terminó tras 5 fallos consecutivos.";
            } elseif ($casillasExitosas === count($casillas)) {
                $partida->setEstado("ganada");
                $resultado = "¡Partida completada con éxito! Todos los héroes superaron sus desafíos.";
            } else {
                $partida->setEstado("en curso");
                $resultado = "La partida sigue activa.";
            }
            
            PartidaDAO::update($partida);
            
            echo json_encode([
                "id_partida" => $id_partida,
                "resultado" => $resultado,
                "casillas_destapadas" => $casillasDestapadas,
                "casillas_exitosas" => $casillasExitosas,
                "fallos_consecutivos" => $fallosConsecutivos
            ]);
            break;

        default:
            echo json_encode(["error" => "Recurso no reconocido."]);
            break;
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
