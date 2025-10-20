<?php
include("../php/conexion.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM cuenta WHERE id_usuario = $id";

    if ($conexion->query($query)) {
        echo "<script>
                alert('Usuario eliminado correctamente');
                window.location.href = 'usuarios.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar el usuario');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('ID no proporcionado');
            window.history.back();
          </script>";
}
?>
