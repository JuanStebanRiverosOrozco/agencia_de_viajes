<?php
include("../php/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    // Encriptar la contraseña
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $sql = "INSERT INTO cuenta (nombre_usuario, contraseña, correo, id_rol)
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre_usuario, $clave_hash, $correo, $rol);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Usuario registrado correctamente');
                window.location.href='usuarios.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Error al registrar el usuario');
                window.history.back();
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Usuario</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="./img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .tooltip {
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s;
      margin-left: 8px;
    }
    .group:hover .tooltip {
      opacity: 1;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-sky-50 via-emerald-50 to-slate-100 font-sans relative overflow-x-hidden">

  <!-- 🧭 Barra lateral -->
  <aside id="sidebar" class="fixed top-0 left-0 h-full w-20 bg-gradient-to-b from-sky-900 via-cyan-800 to-teal-700 text-white flex flex-col justify-between shadow-2xl transition-all duration-300 z-50">
    <div>
      <div id="toggleBtn" class="flex items-center justify-center lg:justify-start gap-4 px-6 py-5 border-b border-cyan-600 cursor-pointer hover:bg-cyan-900 transition-all duration-300">
        <i class="fa-solid fa-earth-americas text-3xl text-white"></i>
        <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide whitespace-nowrap select-none transition-all duration-300">Panel Admin</h1>
      </div>

      <nav class="mt-8 space-y-3">
        <div class="relative group">
          <a href="admi.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-plane-departure text-xl"></i><span class="nav-text hidden">Ingresados</span>
          </a>
          <span class="tooltip">Ingresados</span>
        </div>

        <div class="relative group">
          <a href="usuarios.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-people-group text-xl"></i><span class="nav-text hidden">Usuarios</span>
          </a>
          <span class="tooltip">Usuarios</span>
        </div>

        <div class="relative group">
          <a href="agregar_usuario.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-clipboard-list text-xl"></i><span class="nav-text hidden">Registros</span>
          </a>
          <span class="tooltip">Registros</span>
        </div>

        <div class="relative group">
          <a href="perfil.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-user-gear text-xl"></i><span class="nav-text hidden">Perfil</span>
          </a>
          <span class="tooltip">Perfil</span>
        </div>
      </nav>
    </div>

    <div class="border-t border-cyan-600 py-4 hover:bg-teal-800">
      <div class="relative group">
        <a href="../index.html" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 transition rounded-lg">
          <i class="fa-solid fa-door-open"></i>
          <span class="nav-text hidden">Cerrar sesión</span>
        </a>
        <span class="tooltip">Cerrar sesión</span>
      </div>
    </div>
  </aside>

  <!-- 🧠 Contenido principal -->
  <main id="mainContent" class="p-8 pl-28 sm:pl-32 lg:pl-36 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-3xl p-8 border border-slate-200">
      <h1 class="text-3xl font-bold text-center text-teal-800 mb-6">➕ Agregar Usuario</h1>

      <form method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de usuario</label>
          <input type="text" name="nombre_usuario" required placeholder="Ingrese nombre" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Correo</label>
          <input type="email" name="correo" required placeholder="correo@ejemplo.com" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
          <input type="password" name="clave" required placeholder="Ingrese una contraseña" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
          <select name="rol" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400">
            <option value="">Seleccionar rol...</option>
            <option value="1">Administrador</option>
            <option value="2">Usuario</option>
            <option value="3">Invitado</option>
          </select>
        </div>

        <div class="grid justify-between mt-6 md:flex">
          <a href="usuarios.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancelar</a>
          <button type="submit" class="bg-gradient-to-r from-teal-700 to-cyan-600 hover:from-teal-800 hover:to-cyan-700 text-white px-4 py-2 rounded-lg font-semibold">Registrar</button>
        </div>
      </form>
    </div>
  </main>

  <script type="module" src="../js/menu.js"></script>
</body>
</html>
