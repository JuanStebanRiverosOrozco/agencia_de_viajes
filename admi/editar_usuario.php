<?php
include("../php/conexion.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $resultado = $conexion->query("SELECT * FROM cuenta WHERE id_usuario = $id");

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
    } else {
        echo "Usuario no encontrado.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Usuario</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white via-slate-50 to-sky-50 flex justify-center items-center min-h-screen">

  <form action="actualizar_usuario.php" method="POST" class="bg-white p-8 rounded-xl  border border-slate shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-teal-800 mb-6">Editar Usuario</h2>

    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

    <label class="block mb-2 font-semibold text-gray-700">Nombre:</label>
    <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" class="w-full border p-2 rounded-md mb-4" required>

    <label class="block mb-2 font-semibold text-gray-700">Correo:</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" class="w-full border p-2 rounded-md mb-4" required>

    <label class="block mb-2 font-semibold text-gray-700">Rol:</label>
    <select name="id_rol" class="w-full border p-2 rounded-md mb-4">
      <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
      <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
      <option value="3" <?= $usuario['id_rol'] == 3 ? 'selected' : '' ?>>Invitado</option>
    </select>

    <div class="flex justify-between">
      <a href="usuarios.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Cancelar</a>
      <button type="submit" class="bg-gradient-to-r from-teal-700 to-cyan-600 hover:from-teal-800 hover:to-cyan-700 text-white px-4 py-2 rounded-md">Actualizar</button>
    </div>
  </form>

</body>
</html>
