<?php
// invitado/mis_reservas.php
session_start();
include('../../php/conexion.php'); 

// VERIFICAR QUE SOLO USUARIOS CON ROL 3 PUEDAN ACCEDER
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 3) {
    header('Location: ../../registro-login.html');
    exit;
}

// Obtener nombre del usuario e ID
$nombre_invitado = isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : "Invitado";
$id_usuario = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;

// Consultar las reservas del usuario logueado con información de la provincia
$sql = "SELECT r.*, p.nombre as nombre_provincia 
        FROM reservas r 
        LEFT JOIN provincia p ON r.id_provincia = p.id_provincia 
        WHERE r.id_usuario = ? 
        ORDER BY r.fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
$reservas = [];

if ($stmt) {
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $reservas[] = $fila;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="../../img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
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

    /* Animación de entrada */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }

    /* Tabla responsive */
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tr {
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        overflow: hidden;
      }
      td {
        text-align: right;
        padding-left: 50%;
        position: relative;
      }
      td:before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        font-weight: 600;
        text-align: left;
      }
      .acciones {
        flex-direction: column !important;
      }
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
        <h1 id="sidebar-title" class="hidden text-xl font-semibold tracking-wide select-none">Panel Cliente</h1>
      </div>

      <!-- Navegación -->
      <nav class="mt-8 space-y-3">
        <div class="relative group">
          <a href="cliente.php" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
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

        <div class="relative group">
          <a href="mis_reservas.php" class="flex items-center gap-3 px-6 py-3 bg-cyan-800 rounded-lg transition">
            <i class="fa-solid fa-clipboard-list text-xl"></i>
            <span class="nav-text hidden">Mis Reservas</span>
          </a>
          <span class="tooltip">Mis Reservas</span>
        </div>
      </nav>
    </div>

    <div class="border-t border-cyan-700 py-4 hover:bg-cyan-800">
      <div class="relative group">
        <a href="../../index.html" class="flex items-center gap-3 px-6 py-3 transition rounded-lg">
          <i class="fa-solid fa-door-open"></i>
          <span class="nav-text hidden">Cerrar sesión</span>
        </a>
        <span class="tooltip">Cerrar sesión</span>
      </div>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main id="mainContent" class="ml-20 p-6 pl-8 sm:pl-10 lg:pl-16 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    
    <!-- Encabezado -->
    <header class="mb-8 mt-4 fade-in-up">
      <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight flex items-center gap-3">
        <i class="fa-solid fa-clipboard-list text-teal-600"></i>
        Mis Reservas
      </h1>
      <p class="text-slate-600 mt-1">Gestiona y consulta todas tus reservas de viajes</p>
    </header>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in-up">
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Total de Reservas</p>
            <p class="text-3xl font-bold text-teal-800"><?= count($reservas) ?></p>
          </div>
          <div class="bg-teal-100 p-4 rounded-xl">
            <i class="fa-solid fa-ticket text-teal-600 text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Cliente</p>
            <p class="text-xl font-bold text-sky-800"><?= $nombre_invitado ?></p>
          </div>
          <div class="bg-sky-100 p-4 rounded-xl">
            <i class="fa-solid fa-user text-sky-600 text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Nueva Reserva</p>
            <a href="destinos.php" class="text-emerald-700 font-semibold hover:underline">Explorar destinos →</a>
          </div>
          <div class="bg-emerald-100 p-4 rounded-xl">
            <i class="fa-solid fa-plus-circle text-emerald-600 text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200 fade-in-up">
      <?php if (empty($reservas)): ?>
        <!-- Sin reservas -->
        <div class="p-12 text-center">
          <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-bold text-gray-800 mb-2">No tienes reservas aún</h3>
          <p class="text-gray-600 mb-6">Comienza a explorar nuestros destinos y realiza tu primera reserva</p>
          <a href="destinos.php" class="inline-block bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fa-solid fa-plane-departure mr-2"></i>
            Explorar Destinos
          </a>
        </div>
      <?php else: ?>
        <!-- Con reservas -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm text-slate-700">
            <thead class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-left">
              <tr>
                <th class="py-4 px-6 font-semibold">ID</th>
                <th class="py-4 px-6 font-semibold">Destino</th>
                <th class="py-4 px-6 font-semibold">Fecha del Viaje</th>
                <th class="py-4 px-6 font-semibold">Personas</th>
                <th class="py-4 px-6 font-semibold">Teléfono</th>
                <th class="py-4 px-6 font-semibold">Fecha de Reserva</th>
                <th class="py-4 px-6 font-semibold">Comentarios</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservas as $reserva): ?>
              <tr class="odd:bg-white even:bg-slate-50 hover:bg-teal-50 transition-colors duration-200">
                <td class="py-3 px-6 font-semibold" data-label="ID">
                  #<?= $reserva['id_reserva'] ?>
                </td>
                <td class="py-3 px-6" data-label="Destino">
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-teal-600"></i>
                    <span class="font-medium"><?= htmlspecialchars($reserva['nombre_provincia'] ?? 'Destino desconocido') ?></span>
                  </div>
                </td>
                <td class="py-3 px-6" data-label="Fecha del Viaje">
                  <?php if (!empty($reserva['fecha_viaje'])): ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-calendar-days text-sky-600"></i>
                      <span><?= date('d/m/Y', strtotime($reserva['fecha_viaje'])) ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-gray-400 italic">No especificada</span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-6" data-label="Personas">
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-purple-600"></i>
                    <span class="font-medium"><?= $reserva['personas'] ?></span>
                  </div>
                </td>
                <td class="py-3 px-6" data-label="Teléfono">
                  <?php if (!empty($reserva['telefono'])): ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-phone text-emerald-600"></i>
                      <span><?= htmlspecialchars($reserva['telefono']) ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-gray-400 italic">No proporcionado</span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-6" data-label="Fecha de Reserva">
                  <div class="text-xs text-gray-600">
                    <?= date('d/m/Y H:i', strtotime($reserva['fecha_creacion'])) ?>
                  </div>
                </td>
                <td class="py-3 px-6" data-label="Comentarios">
                  <?php if (!empty($reserva['comentarios'])): ?>
                    <div class="max-w-xs">
                      <p class="text-sm text-gray-700 line-clamp-2" title="<?= htmlspecialchars($reserva['comentarios']) ?>">
                        <?= htmlspecialchars($reserva['comentarios']) ?>
                      </p>
                    </div>
                  <?php else: ?>
                    <span class="text-gray-400 italic">Sin comentarios</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- Información adicional -->
    <div class="mt-8 bg-gradient-to-r from-teal-50 to-cyan-50 border-l-4 border-teal-500 p-6 rounded-lg fade-in-up">
      <div class="flex items-start gap-4">
        <i class="fa-solid fa-circle-info text-teal-600 text-2xl mt-1"></i>
        <div>
          <h3 class="font-bold text-teal-900 mb-2">Información sobre tus reservas</h3>
          <ul class="text-sm text-teal-800 space-y-1">
            <li>• Nos pondremos en contacto contigo en las próximas 24 horas para confirmar tu reserva.</li>
            <li>• Si necesitas modificar alguna reserva, por favor contacta con nuestro equipo de soporte.</li>
            <li>• Todas las reservas están sujetas a disponibilidad y confirmación.</li>
          </ul>
        </div>
      </div>
    </div>

  </main>
  
  <script type="module" src="../../js/menu.js"></script>
</body>
</html>