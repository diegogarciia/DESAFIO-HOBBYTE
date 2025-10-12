<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../Controller/UsuarioController.php';

$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$archivo = basename(__FILE__);
$index = array_search($archivo, $uri);
$recurso = $uri[$index + 1] ?? null;
$accion = $uri[$index + 2] ?? null;

$input = json_decode(file_get_contents("php://input"), true);

if (!$recurso) {
    echo json_encode(["error" => "Debe especificar un recurso (login, admin, jugador, etc)."]);
    exit;
}

switch ($recurso) {

    case "login":
        $correo = $input["correo"] ?? "";
        $contrasena = $input["contraseña"] ?? "";

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
        if (!$accion) {
            echo json_encode(["error" => "Debe indicar una acción dentro de /admin (por ejemplo, /cambiarContrasena)."]);
            break;
        }

        switch ($accion) {

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
                $correo = $input["email"] ?? "";
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
        if (!$accion) {
            echo json_encode(["error" => "Debe indicar una acción dentro de /jugador."]);
            break;
        }

        switch ($accion) {

            case "listar":
                $jugadores = UsuarioController::listarUsuarios();
                echo json_encode($jugadores);
                break;

            default:
                echo json_encode(["error" => "Acción de jugador no reconocida."]);
                break;
        }
        break;

    default:
        echo json_encode(["error" => "Recurso no reconocido."]);
        break;
}
