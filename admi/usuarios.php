<?php
include("../php/conexion.php");
$resultado = $conexion->query("SELECT * FROM cuenta ORDER BY id_usuario ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Usuarios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="../img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
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
          <a href="provincias.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-map-location-dot text-xl"></i>
          <span class="nav-text hidden">Provincias</span>
          </a>
        <span class="tooltip">Provincias</span>
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
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-200">
      <h1 class="text-3xl font-bold mb-6 text-center text-teal-800">👥 Gestión de Usuarios</h1>

      <div class="flex justify-end mb-6">
        <a href="agregar_usuario.php" class="bg-gradient-to-r from-teal-700 to-cyan-600 hover:from-teal-800 hover:to-cyan-700 text-white px-5 py-2 rounded-md shadow-md transition-all duration-200">
          + Agregar Usuario
        </a>
      </div>

      <div class="overflow-x-auto rounded-2xl shadow-md border border-slate-200">
        <table class="min-w-full text-sm text-slate-700">
          <thead class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-left">
            <tr>
              <th class="py-4 px-6 font-semibold">ID</th>
              <th class="py-4 px-6 font-semibold">Nombre</th>
              <th class="py-4 px-6 font-semibold">Correo</th>
              <th class="py-4 px-6 font-semibold">Rol</th>
              <th class="py-4 px-6 font-semibold text-center">Acciones</th>
            </tr>
          </thead>
<tbody>
  <?php while($fila = $resultado->fetch_assoc()) { ?>
  <tr class="odd:bg-white even:bg-slate-50 hover:bg-emerald-50 transition-colors duration-200">
    <td class="py-3 px-6" data-label="ID"><?= $fila['id_usuario'] ?></td>
    <td class="py-3 px-6" data-label="Nombre"><?= htmlspecialchars($fila['nombre_usuario']) ?></td>
    <td class="py-3 px-6" data-label="Correo"><?= htmlspecialchars($fila['correo']) ?></td>
    <td class="py-3 px-6" data-label="Rol">
      <?php
        switch ($fila['id_rol']) {
          case 1: echo "<span class='text-teal-700 font-medium'>Administrador</span>"; break;
          case 2: echo "<span class='text-sky-700 font-medium'>Usuario</span>"; break;
          case 3: echo "<span class='text-emerald-700 font-medium'>Invitado</span>"; break;
          default: echo "<span class='text-gray-500'>Desconocido</span>";
        }
      ?>
    </td>
    <td class="py-3 px-6 text-center acciones flex justify-center space-x-2 md:flex-row">
      <a href="editar_usuario.php?id=<?= $fila['id_usuario'] ?>"
         class="bg-amber-400 hover:bg-amber-500 text-white px-3 py-1 rounded-md shadow-sm transition w-28">Editar</a>
      <a href="eliminar_usuario.php?id=<?= $fila['id_usuario'] ?>"
         class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1 rounded-md shadow-sm transition w-28"
         onclick="return confirm('¿Deseas eliminar este usuario?')">Eliminar</a>
    </td>
  </tr>
  <?php } ?>
</tbody>
        </table>
      </div>
    </div>
  </main>

  <script type="module" src="../js/menu.js"></script>
</body>
</html>
