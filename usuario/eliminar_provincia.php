<?php
include("../php/conexion.php");
session_start();
$id = $_GET['id'] ?? null;
$usuario = $_SESSION['id_rol'];
$conexion->query("SET @usuario_app = '$usuario'");

if (!$id) {
    header("Location: provincias.php");
    exit;
}

if ($id) {
    $stmt = $conexion->prepare("DELETE FROM provincia WHERE id_provincia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: provincias.php");
exit;
?>
