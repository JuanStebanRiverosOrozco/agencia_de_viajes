<?php
include("../php/conexion.php");
session_start();
$id = $_GET['id'] ?? null;
$usuario = $_SESSION['id_rol'];
$conexion->query("SET @usuario_app = '$usuario'");

if (!$id) {
    header("Location: reservas.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $telefono = trim($_POST["telefono"]);
    $fecha_viaje = trim($_POST["fecha_viaje"]);
    $personas = trim($_POST["personas"]);
    $comentarios = trim($_POST["comentarios"]);
    $id_provincia = trim($_POST["id_provincia"]);

    if (!empty($correo) && !empty($telefono)) {
        $stmt = $conexion->prepare("UPDATE reservas SET correo = ?, telefono = ?, fecha_viaje = ?, personas = ?, comentarios = ?, id_provincia = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssissi", $correo, $telefono, $fecha_viaje, $personas, $comentarios, $id_provincia, $id);
        $stmt->execute();

        header("Location: reservas.php");
        exit;
    } else {
        $error = "El correo y teléfono son obligatorios.";
    }
}

// Obtener datos actuales de la reserva
$stmt = $conexion->prepare("
  SELECT 
    r.id_usuario,
    u.nombre_usuario,
    r.correo,
    r.telefono,
    r.fecha_viaje,
    r.personas,
    r.comentarios,
    r.id_provincia,
    p.nombre AS nombre_provincia
  FROM reservas r
  LEFT JOIN cuenta u ON r.id_usuario = u.id_usuario
  LEFT JOIN provincia p ON r.id_provincia = p.id_provincia
  WHERE r.id_usuario = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

// Obtener lista de provincias para el select
$provincias = $conexion->query("SELECT id_provincia, nombre FROM provincia ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Reserva</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="../img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-200 max-w-3xl mx-auto">
      <h1 class="text-3xl font-bold mb-6 text-center text-teal-800">✏️ Editar Reserva</h1>

      <?php if (!empty($error)) echo "<p class='text-red-500 mb-4 text-center bg-red-50 p-3 rounded-lg'>$error</p>"; ?>

      <form method="POST" class="space-y-6">
        <!-- Información del Usuario (solo lectura) -->
        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 p-5 rounded-xl border border-teal-200">
          <h2 class="text-lg font-semibold text-teal-800 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-user"></i>
            Información del Usuario
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">ID Usuario</label>
              <input type="text" value="<?= htmlspecialchars($reserva['id_usuario']) ?>" 
                class="w-full px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600" readonly>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Usuario</label>
              <input type="text" value="<?= htmlspecialchars($reserva['nombre_usuario']) ?>" 
                class="w-full px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600" readonly>
            </div>
          </div>
        </div>

        <!-- Datos de Contacto -->
        <div class="bg-gradient-to-r from-sky-50 to-blue-50 p-5 rounded-xl border border-sky-200">
          <h2 class="text-lg font-semibold text-sky-800 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-address-book"></i>
            Datos de Contacto
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico *</label>
              <input type="email" name="correo" value="<?= htmlspecialchars($reserva['correo']) ?>" 
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-400 focus:border-transparent" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono *</label>
              <input type="text" name="telefono" value="<?= htmlspecialchars($reserva['telefono']) ?>" 
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-400 focus:border-transparent" required>
            </div>
          </div>
        </div>

        <!-- Detalles de la Reserva -->
        <div class="bg-gradient-to-r from-emerald-50 to-green-50 p-5 rounded-xl border border-emerald-200">
          <h2 class="text-lg font-semibold text-emerald-800 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-calendar-check"></i>
            Detalles de la Reserva
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Fecha del Viaje</label>
              <input type="date" name="fecha_viaje" value="<?= htmlspecialchars($reserva['fecha_viaje']) ?>" 
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Número de Personas</label>
              <input type="number" name="personas" value="<?= htmlspecialchars($reserva['personas']) ?>" min="1"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent">
            </div>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provincia</label>
            <select name="id_provincia" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent">
              <option value="">-- Seleccionar Provincia --</option>
              <?php while ($prov = $provincias->fetch_assoc()): ?>
                <option value="<?= $prov['id_provincia'] ?>" <?= ($prov['id_provincia'] == $reserva['id_provincia']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($prov['nombre']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-medium text-slate-700 mb-1">Comentarios</label>
            <textarea name="comentarios" rows="4" 
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent"
              placeholder="Comentarios adicionales sobre la reserva"><?= htmlspecialchars($reserva['comentarios']) ?></textarea>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-between pt-4 gap-4">
          <a href="reservas.php" class="flex-1 px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white rounded-lg font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg">
            <i class="fa-solid fa-arrow-left mr-2"></i>Cancelar
          </a>
          <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fa-solid fa-save mr-2"></i>Actualizar Reserva
          </button>
        </div>
      </form>
    </div>
  </main>

  <!-- Script externo del menú -->
  <script src="../js/menu.js"></script>
</body>
</html>