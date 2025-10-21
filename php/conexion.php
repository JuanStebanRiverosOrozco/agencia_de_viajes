<?php
// Datos de conexión
$servidor = 'localhost';
$basedatos = 'almacen';
$usuario = 'root';
$contrasena = '';

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conexion->connect_errno) {
    die("❌ Error de conexión a la base de datos: " . $conexion->connect_error);
} else {
    // echo "<h1>✅ Conectado correctamente</h1>"; // Puedes dejarlo comentado para no mostrarlo en producción
}
?>
