<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Usuario</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

    /* Sidebar overlay y posición */
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

    /* Fijar contenido */
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

  <!-- 🧠 Contenido principal -->
  <main id="mainContent" class="p-6 pl-8 sm:pl-10 lg:pl-16 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <header class="mb-10 mt-4">
      <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight">Bienvenido al Panel de Usuario</h1>
      <p class="text-slate-600 mt-2">Gestiona tus provincias, reservas y configuración personal fácilmente.</p>
    </header>

    <!-- Tarjetas -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Provincias -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-sky-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-map-location-dot text-sky-700 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Gestión de Provincias</h2>
        <p class="text-gray-600 mt-2 mb-4">Crea, edita o elimina provincias asociadas a tus destinos.</p>
        <a href="provincias.php" class="inline-block bg-sky-600 hover:bg-sky-700 text-white font-medium px-5 py-2 rounded-lg transition">Ir a Provincias</a>
      </div>

      <!-- Reservas -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-400/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-calendar-check text-green-600 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Estado de Reservas</h2>
        <p class="text-gray-600 mt-2 mb-4">Consulta las reservas que has realizado y su estado actual.</p>
        <a href="reservas.php" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-lg transition">Ver Reservas</a>
      </div>

      <!-- Configuración -->
      <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all border border-slate-200">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-yellow-300/20 to-transparent rounded-bl-full"></div>
        <i class="fa-solid fa-gear text-yellow-500 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold text-teal-800">Configuración</h2>
        <p class="text-gray-600 mt-2 mb-4">Actualiza tu información personal o cambia tu contraseña.</p>
        <a href="perfil.php" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-5 py-2 rounded-lg transition">Ir a Configuración</a>
      </div>
    </section>
  </main>

  <!-- Script externo del menú -->
  <script src="../js/menu.js"></script>
</body>
</html>
