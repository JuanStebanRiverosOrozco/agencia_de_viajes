<?php
include('../php/conexion.php');

$totalUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM cuenta")->fetch_assoc()['total'];
$totalAdmins = $conexion->query("SELECT COUNT(*) AS total FROM cuenta WHERE id_rol = 1")->fetch_assoc()['total'];
$ultimoUsuario = $conexion->query("SELECT nombre_usuario FROM cuenta ORDER BY id_usuario DESC LIMIT 1")->fetch_assoc()['nombre_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel del Administrador</title>
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

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-0 left-0 h-full w-20 bg-gradient-to-b from-sky-900 via-cyan-800 to-teal-700 text-white flex flex-col justify-between shadow-2xl transition-all duration-300 z-50">
    <div>
<!-- LOGO = BOTÓN --> 
 <div id="toggleBtn" class="flex items-center justify-center lg:justify-start gap-4 px-6 py-5 border-b border-cyan-600 
 cursor-pointer hover:bg-cyan-900 transition-all duration-300"> <i class="fa-solid fa-earth-americas text-3xl text-white">
 </i> <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide whitespace-nowrap select-none transition-all
 duration-300">Panel Admin</h1> 
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
          <a href="#" class="flex items-center justify-center lg:justify-start gap-3 px-6 py-3 hover:bg-teal-800 transition rounded-lg">
            <i class="fa-solid fa-chart-pie text-xl"></i><span class="nav-text hidden">Estadísticas</span>
          </a>
          <span class="tooltip">Estadísticas</span>
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

    <!-- Dashboard -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
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
  </main>

<script type="module" src="../js/menu.js"></script>
</body>
</html>
