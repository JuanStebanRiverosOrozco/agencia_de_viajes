<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';

    $stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario, contraseña, id_rol FROM cuenta WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($clave, $user['contraseña'])) {
            // Guardar sesión
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            $_SESSION['id_rol'] = $user['id_rol'];

            // Redirección según el rol
            switch ($user['id_rol']) {
                case 1: // Administrador
                    header("Location: ../admi/admi.php");
                    break;
                case 2: // Usuario (gestiona provincias y reservas)
                    header("Location: ../usuario/usuario.php");
                    break;
                case 3: // Invitado
                    header("Location: ../cliente-invitado/cliente-invitado.php");
                    break;
                default:
                    echo "<script>alert('Rol no válido'); window.history.back();</script>";
                    exit;
            }
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
        exit;
    }
}
?>

