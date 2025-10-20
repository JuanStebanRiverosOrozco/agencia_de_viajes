<?php
session_start();
include("../php/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
  echo "<script>alert('Error: sesión no iniciada'); window.location.href = '../login.php';</script>";
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = trim($_POST['nombre_usuario'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$clave = $_POST['clave'] ?? '';

if (empty($nombre) || empty($correo)) {
  echo "<script>alert('Por favor completa los campos requeridos'); window.history.back();</script>";
  exit;
}

// Actualizar según si el usuario ingresó una nueva contraseña o no
if (!empty($clave)) {
  $hash = password_hash($clave, PASSWORD_DEFAULT);
  $stmt = $conexion->prepare("UPDATE cuenta SET nombre_usuario=?, correo=?, contraseña=? WHERE id_usuario=?");
  $stmt->bind_param("sssi", $nombre, $correo, $hash, $id_usuario);
} else {
  $stmt = $conexion->prepare("UPDATE cuenta SET nombre_usuario=?, correo=? WHERE id_usuario=?");
  $stmt->bind_param("ssi", $nombre, $correo, $id_usuario);
}

if ($stmt->execute()) {
  // Actualiza la sesión con los nuevos datos
  $_SESSION['nombre_usuario'] = $nombre;

  echo "<script>
    alert('✅ Datos actualizados correctamente');
    window.location.href = 'perfil.php';
  </script>";
} else {
  echo "<script>
    alert('❌ Error al actualizar los datos');
    window.history.back();
  </script>";
}
?>
