<?php
include("../php/conexion.php");

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conexion->prepare("DELETE FROM provincia WHERE id_provincia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: provincias.php");
exit;
?>
