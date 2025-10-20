<?php
include("../php/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id_usuario']);
    $nombre = $conexion->real_escape_string($_POST['nombre_usuario']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $rol = intval($_POST['id_rol']);

    $query = "UPDATE cuenta
              SET nombre_usuario='$nombre', correo='$correo', id_rol=$rol 
              WHERE id_usuario=$id";

    if ($conexion->query($query)) {
        echo "<script>
                alert('Usuario actualizado correctamente');
                window.location.href = 'usuarios.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al actualizar el usuario');
                window.history.back();
              </script>";
    }
}
?>
