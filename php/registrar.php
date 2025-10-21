<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    // Asignar id numérico según el rol
    switch ($rol) {
        case "administrador":
            $id_rol = 1;
            break;
        case "usuario":
            $id_rol = 2;
            break;
        case "invitado":
            $id_rol = 3;
            break;
        default:
            $id_rol = 3;
    }

    // Encriptar la contraseña antes de guardar
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Preparar consulta SQL
    $sql = "INSERT INTO cuenta (nombre_usuario, contraseña, correo, fecha_creacion, id_rol)
            VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssi", $nombre_usuario, $clave_hash, $correo, $id_rol);
        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
                    window.location.href = '../registro-login.html';
                  </script>";
        } else {
            echo "<script>
                    alert('❌ Error al registrar: " . $stmt->error . "');
                    window.history.back();
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('❌ Error al preparar la consulta.');
                window.history.back();
              </script>";
    }

    $conexion->close();
}
?>
