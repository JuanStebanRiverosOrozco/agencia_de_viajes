<?php
include("../php/conexion.php");
session_start();
$id = $_GET['id'] ?? null;
$usuario = $_SESSION['id_rol'];
$conexion->query("SET @usuario_app = '$usuario'");

if ($id) {
    $stmt = $conexion->prepare("DELETE FROM reservas WHERE id_reserva = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: reservas.php");
exit;
?>
