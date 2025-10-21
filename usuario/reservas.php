<?php
include("../php/conexion.php");

// Consultar datos con JOIN a provincias y usuarios
$query = "
  SELECT 
    r.id_usuario,
    u.nombre_usuario AS nombre_usuario,
    r.correo,
    r.telefono,
    r.fecha_viaje,
    r.personas,
    r.comentarios,
    r.fecha_creacion,
    p.nombre AS nombre_provincia
  FROM reservas r
  LEFT JOIN cuenta u ON r.id_usuario = u.id_usuario
  LEFT JOIN provincia p ON r.id_provincia = p.id_provincia
  ORDER BY r.fecha_creacion DESC
";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Estado de Reservas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    /* Tooltip */
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

    /* Sidebar fijo */
    #sidebar {
      transition: width 0.3s ease;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      z-index: 50;
    }

    #sidebar.w-64 {
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
    }

    /* Contenido principal */
    #mainContent {
      margin-left: 5rem;
      transition: none;
    }
  </style>
</head>

<body class="bg-gradient-to-b from-sky-50 via-emerald-50 to-slate-100 font-sans relative overflow-x-hidden">

  <!-- 🌊 Sidebar -->
  <aside id="sidebar" class="bg-gradient-to-b from-sky-900 via-cyan-800 to-teal-700 text-white flex flex-col justify-between shadow-2xl w-20">
    <div>
      <!-- Logo / Toggle -->
      <div id="toggleBtn" class="flex items-center gap-4 px-6 py-5 border-b border-cyan-600 cursor-pointer hover:bg-cyan-900 transition-all duration-300">
        <i class="fa-solid fa-earth-americas text-3xl text-white"></i>
        <h1 id="sidebar-title" class="text-xl font-semibold tracking-wide select-none hidden">Panel Usuario</h1>
      </div>

      <!-- Navegación -->
      <nav class="mt-8 space-y-3">
        <div class="relative group">
          <a href="usuario.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="nav-text hidden">Inicio</span>
          </a>
          <span class="tooltip">Inicio</span>
        </div>

        <div class="relative group">
          <a href="provincias.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-map-location-dot text-xl"></i>
            <span class="nav-text hidden">Provincias</span>
          </a>
          <span class="tooltip">Provincias</span>
        </div>

        <div class="relative group">
          <a href="reservas.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-calendar-days text-xl"></i>
            <span class="nav-text hidden">Reservas</span>
          </a>
          <span class="tooltip">Reservas</span>
        </div>

        <div class="relative group">
          <a href="perfil.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-user-gear text-xl"></i>
            <span class="nav-text hidden">Perfil</span>
          </a>
          <span class="tooltip">Perfil</span>
        </div>
      </nav>
    </div>

    <div class="border-t border-cyan-600 py-4 hover:bg-teal-800">
      <div class="relative group">
        <a href="../index.html" class="flex items-center gap-3 px-6 py-3 transition rounded-lg">
          <i class="fa-solid fa-door-open"></i>
          <span class="nav-text hidden">Cerrar sesión</span>
        </a>
        <span class="tooltip">Cerrar sesión</span>
      </div>
    </div>
  </aside>

  <!-- 🧭 Contenido principal -->
  <main id="mainContent" class="p-8 pl-28 sm:pl-32 lg:pl-36 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-200">
      <h1 class="text-3xl font-bold mb-6 text-center text-teal-800">📊 Estado de Reservas</h1>

      <div class="overflow-x-auto rounded-2xl shadow-md border border-slate-200">
        <table class="min-w-full text-sm text-slate-700">
          <thead class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-left">
            <tr>
              <th class="py-4 px-6 font-semibold">ID</th>
              <th class="py-4 px-6 font-semibold">Usuario</th>
              <th class="py-4 px-6 font-semibold">Correo</th>
              <th class="py-4 px-6 font-semibold">Teléfono</th>
              <th class="py-4 px-6 font-semibold">Fecha del viaje</th>
              <th class="py-4 px-6 font-semibold">Personas</th>
              <th class="py-4 px-6 font-semibold">Comentarios</th>
              <th class="py-4 px-6 font-semibold">Fecha creación</th>
              <th class="py-4 px-6 font-semibold">Provincia</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr class="odd:bg-white even:bg-slate-50 hover:bg-emerald-50 transition-colors duration-200">
              <td class="py-3 px-6"><?= $fila['id_usuario'] ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['nombre_usuario']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['correo']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['telefono']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['fecha_viaje']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['personas']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['comentarios']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['fecha_creacion']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($fila['nombre_provincia'] ?? '—') ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Script externo del menú -->
  <script src="../js/menu.js"></script>
</body>
</html>
