<?php
session_start();
include("../php/conexion.php");

// Verificamos sesión activa
if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../inicio_registro.html");
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$query = $conexion->query("SELECT * FROM cuenta WHERE id_usuario = $id_usuario");
$usuario = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuraciones - Panel de Administrador</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
      <!-- 🌍 LOGO / BOTÓN -->
      <div id="toggleBtn" class="flex items-center justify-center lg:justify-start gap-4 px-6 py-5 border-b border-cyan-600 cursor-pointer hover:bg-cyan-900 transition-all duration-300">
        <i class="fa-solid fa-earth-americas text-3xl text-white"></i>
        <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide whitespace-nowrap select-none transition-all duration-300">Panel Admin</h1>
      </div>

      <!-- Menú -->
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
          <a href="estadisticas.html" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-chart-pie text-xl"></i><span class="nav-text hidden">Estadísticas</span>
          </a>
          <span class="tooltip">Estadísticas</span>
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
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">⚙️ Configuración de Cuenta</h1>

    <div class="max-w-lg mx-auto bg-white p-8 rounded-3xl shadow-lg border border-slate-200">
      <form action="./actualizar_perfil.php" method="POST" class="space-y-6">

        <!-- Nombre -->
        <div>
          <label class="block font-semibold text-gray-700 mb-2">Nombre de usuario</label>
          <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
        </div>

        <!-- Correo -->
        <div>
          <label class="block font-semibold text-gray-700 mb-2">Correo electrónico</label>
          <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
        </div>

        <!-- Contraseña -->
        <div>
          <label class="block font-semibold text-gray-700 mb-2">Nueva contraseña</label>
          <input type="password" name="clave" placeholder="Deja en blanco si no deseas cambiarla"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>

        <!-- Botón -->
        <div class="text-center">
          <button type="submit"
                  class="bg-gradient-to-r from-teal-700 to-cyan-600 hover:from-teal-800 hover:to-cyan-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            Guardar cambios
          </button>
        </div>

      </form>
    </div>
  </main>

  <script type="module" src="../js/menu.js"></script>
</body>
</html>
