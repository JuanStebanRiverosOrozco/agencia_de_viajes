<?php
include('../php/conexion.php');

$totalUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM cuenta")->fetch_assoc()['total'];
$totalAdmins = $conexion->query("SELECT COUNT(*) AS total FROM cuenta WHERE id_rol = 1")->fetch_assoc()['total'];
$totalClientes = $conexion->query("SELECT COUNT(*) AS total FROM cuenta WHERE id_rol = 2")->fetch_assoc()['total'];
$totalInvitados = $conexion->query("SELECT COUNT(*) AS total FROM cuenta WHERE id_rol = 3")->fetch_assoc()['total'];
$ultimoUsuario = $conexion->query("SELECT nombre_usuario FROM cuenta ORDER BY id_usuario DESC LIMIT 1")->fetch_assoc()['nombre_usuario'];

// Estadísticas de provincias/destinos
$totalProvincias = $conexion->query("SELECT COUNT(*) AS total FROM provincia")->fetch_assoc()['total'];
$provinciaPopular = $conexion->query("SELECT nombre FROM provincia ORDER BY id_provincia DESC LIMIT 1")->fetch_assoc()['nombre'] ?? 'N/A';

// Usuarios por rol (para gráfico)
$usuariosPorRol = [
    'Administradores' => $totalAdmins,
    'Clientes' => $totalClientes,
    'Invitados' => $totalInvitados
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrador</title>
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
    .progress-bar {
      transition: width 0.5s ease;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-sky-50 via-emerald-50 to-slate-100 font-sans relative overflow-x-hidden">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-0 left-0 h-full w-20 bg-gradient-to-b from-sky-900 via-cyan-800 to-teal-700 text-white flex flex-col justify-between shadow-2xl transition-all duration-300 z-50">
    <div>
      <!-- LOGO = BOTÓN --> 
      <div id="toggleBtn" class="flex items-center justify-center lg:justify-start gap-4 px-6 py-5 border-b border-cyan-600 cursor-pointer hover:bg-cyan-900 transition-all duration-300">
        <i class="fa-solid fa-earth-americas text-3xl text-white"></i>
        <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide whitespace-nowrap select-none transition-all duration-300">Panel Admin</h1> 
      </div>

      <!-- NAV -->
      <nav class="mt-8 space-y-3">
        <div class="relative group">
          <a href="admi.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 transition rounded-lg">
            <i class="fa-solid fa-plane-departure text-xl"></i><span class="nav-text hidden">Usuarios Totales</span>
          </a>
          <span class="tooltip">Usuarios Totales</span>
        </div>
        <div class="relative group">
          <a href="usuarios.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 transition rounded-lg">
            <i class="fa-solid fa-people-group text-xl"></i><span class="nav-text hidden">Usuarios</span>
          </a>
          <span class="tooltip">Usuarios</span>
        </div>
        <div class="relative group">
          <a href="agregar_usuario.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 transition rounded-lg">
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
          <a href="reservas.php" class="flex items-center gap-3 px-6 py-3 hover:bg-teal-800 rounded-lg transition">
            <i class="fa-solid fa-calendar-days text-xl"></i>
            <span class="nav-text hidden">Reservas</span>
          </a>
          <span class="tooltip">Reservas</span>
        </div>
        <div class="relative group">
          <a href="perfil.php" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 transition rounded-lg">
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

  <!-- Contenido principal -->
  <main id="mainContent" class="p-8 pl-28 sm:pl-32 lg:pl-36 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <header class="mb-8 mt-4">
      <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight">Bienvenido, Administrador</h1>
      <p class="text-slate-600 mt-2">Gestiona tu sistema de viajes y usuarios con claridad y confort visual.</p>
    </header>

    <!-- Dashboard Principal -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-300/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-user-group text-4xl text-teal-700 mb-3"></i>
        <h3 class="text-lg font-semibold text-teal-800">Usuarios registrados</h3>
        <p class="text-4xl font-bold text-amber-500 mt-2"><?= $totalUsuarios ?></p>
      </div>

      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-cyan-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-crown text-4xl text-teal-700 mb-3"></i>
        <h3 class="text-lg font-semibold text-teal-800">Administradores</h3>
        <p class="text-4xl font-bold text-amber-500 mt-2"><?= $totalAdmins ?></p>
      </div>

      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-sky-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-user-plus text-4xl text-teal-700 mb-3"></i>
        <h3 class="text-lg font-semibold text-teal-800">Último usuario registrado</h3>
        <p class="text-xl font-bold text-slate-700 mt-2"><?= htmlspecialchars($ultimoUsuario) ?></p>
      </div>
    </section>

    <!-- Estadísticas Detalladas -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      
      <!-- Distribución de usuarios por rol -->
      <div class="bg-white rounded-3xl p-6 shadow-lg border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
          <i class="fa-solid fa-chart-pie text-3xl text-sky-600"></i>
          <h2 class="text-2xl font-bold text-teal-800">Distribución por Rol</h2>
        </div>

        <div class="space-y-4">
          <?php 
          $colores = ['bg-sky-600', 'bg-cyan-600', 'bg-teal-600'];
          $i = 0;
          foreach ($usuariosPorRol as $rol => $cantidad): 
            $porcentaje = $totalUsuarios > 0 ? round(($cantidad / $totalUsuarios) * 100, 1) : 0;
          ?>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-semibold text-gray-700"><?= $rol ?></span>
              <span class="text-sm font-bold text-gray-900"><?= $cantidad ?> (<?= $porcentaje ?>%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
              <div class="progress-bar h-full <?= $colores[$i] ?> rounded-full" style="width: <?= $porcentaje ?>%"></div>
            </div>
          </div>
          <?php 
            $i++;
          endforeach; 
          ?>
        </div>
      </div>

      <!-- Estadísticas de Destinos -->
      <div class="bg-white rounded-3xl p-6 shadow-lg border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
          <i class="fa-solid fa-map-location-dot text-3xl text-teal-600"></i>
          <h2 class="text-2xl font-bold text-teal-800">Destinos</h2>
        </div>

        <div class="space-y-6">
          <div class="flex items-center justify-between p-4 bg-gradient-to-r from-sky-50 to-cyan-50 rounded-xl border border-sky-200">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total de Provincias</p>
              <p class="text-3xl font-bold text-sky-700"><?= $totalProvincias ?></p>
            </div>
            <i class="fa-solid fa-globe text-5xl text-sky-300"></i>
          </div>

          <div class="flex items-center justify-between p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200">
            <div>
              <p class="text-sm text-gray-600 mb-1">Último Destino Agregado</p>
              <p class="text-lg font-bold text-teal-700"><?= htmlspecialchars($provinciaPopular) ?></p>
            </div>
            <i class="fa-solid fa-location-dot text-5xl text-teal-300"></i>
          </div>

          <a href="provincias.php" class="block w-full text-center py-3 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
            <i class="fa-solid fa-eye mr-2"></i>Ver todos los destinos
          </a>
        </div>
      </div>

    </section>

    <!-- Resumen Rápido -->
    <section class="bg-white rounded-3xl p-6 shadow-lg border border-slate-200">
      <div class="flex items-center gap-3 mb-6">
        <i class="fa-solid fa-list-check text-3xl text-amber-600"></i>
        <h2 class="text-2xl font-bold text-teal-800">Resumen del Sistema</h2>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-gradient-to-br from-sky-50 to-sky-100 rounded-xl border border-sky-200">
          <i class="fa-solid fa-users text-3xl text-sky-600 mb-2"></i>
          <p class="text-sm text-gray-600">Clientes</p>
          <p class="text-2xl font-bold text-sky-700"><?= $totalClientes ?></p>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl border border-cyan-200">
          <i class="fa-solid fa-user-tie text-3xl text-cyan-600 mb-2"></i>
          <p class="text-sm text-gray-600">Invitados</p>
          <p class="text-2xl font-bold text-cyan-700"><?= $totalInvitados ?></p>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl border border-teal-200">
          <i class="fa-solid fa-map text-3xl text-teal-600 mb-2"></i>
          <p class="text-sm text-gray-600">Provincias</p>
          <p class="text-2xl font-bold text-teal-700"><?= $totalProvincias ?></p>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200">
          <i class="fa-solid fa-percentage text-3xl text-amber-600 mb-2"></i>
          <p class="text-sm text-gray-600">Tasa Admin</p>
          <p class="text-2xl font-bold text-amber-700"><?= $totalUsuarios > 0 ? round(($totalAdmins / $totalUsuarios) * 100, 1) : 0 ?>%</p>
        </div>
      </div>
    </section>

  </main>

<script type="module" src="../js/menu.js"></script>
</body>
</html>