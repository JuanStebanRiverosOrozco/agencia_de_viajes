<?php
session_start();
include("../php/conexion.php");

// Verificar si hay sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.html");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario actual
$stmt = $conexion->prepare("SELECT nombre_usuario, correo FROM cuenta WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Actualizar contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clave_actual = $_POST['clave_actual'] ?? '';
    $clave_nueva = $_POST['clave_nueva'] ?? '';
    $confirmar_clave = $_POST['confirmar_clave'] ?? '';

    $stmt = $conexion->prepare("SELECT contraseña FROM cuenta WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (password_verify($clave_actual, $res['contraseña'])) {
        if ($clave_nueva === $confirmar_clave) {
            $hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
            $update = $conexion->prepare("UPDATE cuenta SET contraseña = ? WHERE id_usuario = ?");
            $update->bind_param("si", $hash, $id_usuario);
            $update->execute();
            $mensaje = "✅ Contraseña actualizada correctamente.";
            $tipo = "exito";
        } else {
            $mensaje = "⚠️ Las contraseñas nuevas no coinciden.";
            $tipo = "error";
        }
    } else {
        $mensaje = "❌ La contraseña actual no es correcta.";
        $tipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración del Usuario</title>
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

  <!-- 🌿 Contenido principal -->
  <main id="mainContent" class="p-8 pl-28 sm:pl-32 lg:pl-36 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-200 max-w-lg mx-auto">
      <h1 class="text-3xl font-bold mb-6 text-center text-teal-800">⚙️ Configuración del Usuario</h1>

      <?php if (isset($mensaje)): ?>
        <div class="mb-6 p-4 rounded-lg text-white <?= $tipo === 'exito' ? 'bg-green-600' : 'bg-red-600' ?>">
          <?= htmlspecialchars($mensaje) ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <div>
          <label class="block text-gray-700 font-medium mb-2">Nombre</label>
          <input type="text" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" disabled class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Correo</label>
          <input type="email" value="<?= htmlspecialchars($usuario['correo']) ?>" disabled class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Contraseña actual</label>
          <input type="password" name="clave_actual" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Nueva contraseña</label>
          <input type="password" name="clave_nueva" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Confirmar nueva contraseña</label>
          <input type="password" name="confirmar_clave" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500">
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-teal-700 to-cyan-600 hover:from-teal-800 hover:to-cyan-700 text-white font-semibold py-2 rounded-lg shadow-md transition">
          <i class="fa-solid fa-key mr-2"></i>Actualizar Contraseña
        </button>
      </form>
    </div>
  </main>

  <!-- Script externo del menú -->
  <script src="../js/menu.js"></script>
</body>
</html>
