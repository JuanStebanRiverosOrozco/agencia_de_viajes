<?php
// invitado/panel_invitado.php
session_start();
include('../../php/conexion.php'); 

// Esta página es para visitantes no logueados o cualquiera
// No requiere autenticación, pero muestra opciones de inicio de sesión
$nombre_invitado = "Visitante";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Invitado</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="./img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Tooltip */
    .tooltip {
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0,0,0,0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s;
      margin-left: 8px;
      z-index: 100;
    }
    .group:hover .tooltip { opacity: 1; }

    /* Sidebar fijo */
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      z-index: 50;
      transition: width 0.3s ease;
    }

    /* Contenido principal con margen */
    #mainContent {
      transition: margin-left 0.3s ease;
    }
  </style>
</head>

<body class="bg-slate-100 font-sans">

  <!-- Sidebar -->
  <aside id="sidebar" class="w-20 bg-gradient-to-b from-sky-900 via-cyan-800 to-teal-700 text-white flex flex-col justify-between shadow-2xl">
    <div>
      <!-- Logo / Toggle -->
      <div id="toggleBtn" class="flex items-center justify-center lg:justify-start gap-4 px-6 py-5 border-b border-cyan-600 cursor-pointer hover:bg-cyan-900 transition-all duration-300">
        <i class="fa-solid fa-earth-americas text-3xl text-white"></i>
        <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide select-none">Panel Invitado</h1>
      </div>

      <!-- Navegación -->
      <nav class="mt-8 space-y-3">
        <div class="relative group">
          <a href="invitado.php" class="flex items-center gap-3 px-6 py-3 bg-cyan-800 rounded-lg transition">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="nav-text hidden">Inicio</span>
          </a>
          <span class="tooltip">Inicio</span>
        </div>

        <div class="relative group">
          <a href="destinos.php" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
            <i class="fa-solid fa-plane-departure text-xl"></i>
            <span class="nav-text hidden">Ver Paquetes</span>
          </a>
          <span class="tooltip">Ver Paquetes</span>
        </div>

        <!-- Botón de Iniciar Sesión en el sidebar -->
        <div class="relative group">
          <a href="../../registro-login.html" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
            <i class="fa-solid fa-user-plus text-xl"></i>
            <span class="nav-text hidden">Iniciar Sesión</span>
          </a>
          <span class="tooltip">Iniciar Sesión</span>
        </div>
      </nav>
    </div>

    <div class="border-t border-cyan-700 py-4 hover:bg-cyan-800">
      <div class="relative group">
        <a href="../../index.html" class="flex items-center gap-3 px-6 py-3 transition rounded-lg">
          <i class="fa-solid fa-door-open"></i>
          <span class="nav-text hidden">Salir</span>
        </a>
        <span class="tooltip">Salir</span>
      </div>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main id="mainContent" class="ml-20 p-6 pl-8 sm:pl-10 lg:pl-16 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <header class="mb-8 mt-4">
      <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight">Bienvenido, <?= $nombre_invitado ?></h1>
      <p class="text-slate-600 mt-1">Explora nuestros destinos y comienza tu próxima aventura.</p>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Explorar destinos -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-sky-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-plane-departure text-sky-700 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Explorar Destinos</h2>
        <p class="text-gray-600 mt-2 mb-4">Descubre los mejores paquetes turísticos para ti.</p>
        <a href="destinos.php" class="inline-block bg-sky-600 hover:bg-sky-700 text-white font-medium px-5 py-2 rounded-lg transition">Ver Paquetes</a>
      </div>

      <!-- Iniciar sesión - SIEMPRE visible para visitantes -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-user-plus text-emerald-600 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Inicia Sesión</h2>
        <p class="text-gray-600 mt-2 mb-4">Accede a tu cuenta para gestionar reservas y preferencias.</p>
        <a href="../registro-login.html" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2 rounded-lg transition">Iniciar Sesión</a>
      </div>

      <!-- Sobre Nosotros -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-teal-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-circle-info text-teal-600 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Sobre Nosotros</h2>
        <p class="text-gray-600 mt-2 mb-4">Conoce más sobre Expreso Internacional y nuestra misión de brindarte experiencias inolvidables.</p>
        <a href="#" class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-medium px-5 py-2 rounded-lg transition">Saber Más</a>
      </div>
    </section>

    <!-- Banner adicional para invitar a registrarse -->
    <section class="mt-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 text-white shadow-2xl">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
          <h2 class="text-3xl font-bold mb-2">¿Listo para tu próxima aventura?</h2>
          <p class="text-emerald-100">Crea una cuenta y disfruta de beneficios exclusivos, descuentos y más.</p>
        </div>
        <a href="../../registro-login.html" 
           class="bg-white text-emerald-700 px-8 py-4 rounded-xl font-bold hover:bg-emerald-50 transition shadow-lg whitespace-nowrap">
          <i class="fa-solid fa-user-plus mr-2"></i>
          Registrarse Ahora
        </a>
      </div>
    </section>
  </main>
 <script type="module" src="../../js/menu.js"></script>
</body>
</html>