<?php
session_start();
include('../../php/conexion.php');

// aceptar destino/provincia tanto por GET como por POST
$id_destino = $_REQUEST['destino'] ?? $_REQUEST['provincia'] ?? null;
if (!$id_destino) {
    echo "<script>alert('No se indicó el destino.'); window.history.back();</script>";
    exit;
}

// Prefill si hay sesión
$id_usuario = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;
$prefill_nombre = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '';
$prefill_correo = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

// Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_viaje = trim($_POST['fecha_viaje'] ?? '');
    $personas = (int)($_POST['personas'] ?? 1);
    $comentarios = trim($_POST['comentarios'] ?? '');
    $id_destino = $_REQUEST['destino'] ?? $_REQUEST['provincia'] ?? $id_destino;

    // Validaciones básicas
    if ($nombre === '' || $correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Completa nombre y un correo válido.'); window.history.back();</script>";
        exit;
    }
    if ($personas < 1) $personas = 1;

    $sql = "INSERT INTO reservas (id_usuario, id_provincia, nombre, correo, telefono, fecha_viaje, personas, comentarios, fecha_creacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "<script>alert('Error al preparar la reserva.'); window.history.back();</script>";
        exit;
    }

    // tipos: id_usuario (i), id_destino (i), nombre (s), correo (s), telefono (s), fecha_viaje (s), personas (i), comentarios (s)
    $types = "iissssis";
    $stmt->bind_param($types,
        $id_usuario,
        $id_destino,
        $nombre,
        $correo,
        $telefono,
        $fecha_viaje,
        $personas,
        $comentarios
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Reserva registrada. Nos contactaremos contigo pronto.');
                window.location.href = '../cliente/destinos.php'; 
              </script>";
        $stmt->close();
        $conexion->close();
        exit;
    } else {
        echo "<script>alert('❌ Error al guardar la reserva. Intenta nuevamente.'); window.history.back();</script>";
        $stmt->close();
        $conexion->close();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reservar Destino</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../img/travel-agency-logo-with-location-icon-illustration-vector.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #99f6e4 100%);
            min-height: 100vh;
        }

        .input-field {
            border: 2px solid #d1fae5;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .input-field:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        .btn-submit {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(20, 184, 166, 0.5);
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .icon-circle {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
        }
    </style>
</head>
<body class="font-sans">
    <div class="max-w-4xl mx-auto p-6 py-12">
        <!-- Back Button -->
        <a href="detalle_destino.php?id=<?= htmlspecialchars($id_destino) ?>" 
           class="inline-flex items-center gap-2 text-teal-700 hover:text-teal-900 font-medium mb-8 transition hover:gap-3 animate-fade-in">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Volver al destino</span>
        </a>

        <!-- Form Card -->
        <div class="form-card rounded-3xl p-8 md:p-12 animate-fade-in">
            
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="icon-circle mx-auto mb-4">
                    <i class="fa-solid fa-calendar-check text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-teal-900 mb-2">Reservar Destino</h1>
                <p class="text-teal-700 text-lg">Completa el formulario para confirmar tu reserva</p>
            </div>

            <!-- Alert Info -->
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border-l-4 border-teal-500 p-4 rounded-lg mb-8 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-teal-600 text-xl mt-1"></i>
                <div class="text-sm text-teal-800">
                    <p class="font-semibold mb-1">¡Estás a un paso de tu aventura!</p>
                    <p>Nos pondremos en contacto contigo en las próximas 24 horas para confirmar todos los detalles.</p>
                </div>
            </div>

            <!-- Form -->
            <form method="post" novalidate class="space-y-6">
                <input type="hidden" name="destino" value="<?= htmlspecialchars($id_destino) ?>">

                <!-- Nombre -->
                <div>
                    <label class="block mb-2">
                        <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-user text-teal-600"></i>
                            Nombre completo <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input name="nombre"
                           required 
                           class="input-field block w-full rounded-xl bg-white"
                           placeholder="Ingresa tu nombre completo"
                           value="<?= htmlspecialchars($prefill_nombre) ?>">
                </div>

                <!-- Correo -->
                <div>
                    <label class="block mb-2">
                        <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-teal-600"></i>
                            Correo electrónico <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input name="correo" 
                           type="email" 
                           required 
                           class="input-field block w-full rounded-xl bg-white"
                           placeholder="ejemplo@correo.com"
                           value="<?= htmlspecialchars($prefill_correo) ?>">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block mb-2">
                        <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-teal-600"></i>
                            Teléfono <span class="text-gray-500 text-xs">(Opcional)</span>
                        </span>
                    </label>
                    <input name="telefono" 
                           type="tel"
                           class="input-field block w-full rounded-xl bg-white"
                           placeholder="+57 300 123 4567">
                </div>

                <!-- Fecha y Personas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2">
                            <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days text-teal-600"></i>
                                Fecha del viaje
                            </span>
                        </label>
                        <input name="fecha_viaje" 
                               type="date" 
                               class="input-field block w-full rounded-xl bg-white">
                    </div>
                    <div>
                        <label class="block mb-2">
                            <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-users text-teal-600"></i>
                                Número de personas
                            </span>
                        </label>
                        <input name="personas" 
                               type="number" 
                               min="1" 
                               value="1" 
                               class="input-field block w-full rounded-xl bg-white">
                    </div>
                </div>

                <!-- Comentarios -->
                <div>
                    <label class="block mb-2">
                        <span class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots text-teal-600"></i>
                            Comentarios adicionales
                        </span>
                    </label>
                    <textarea name="comentarios" 
                              rows="4" 
                              class="input-field block w-full rounded-xl bg-white resize-none"
                              placeholder="Requisitos especiales, preferencias, preguntas..."></textarea>
                </div>

                <!-- Divider -->
                <div class="border-t border-teal-100 my-8"></div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button type="submit" 
                            class="btn-submit w-full sm:flex-1 px-8 py-4 text-white rounded-xl font-bold text-lg flex items-center justify-center gap-3">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Enviar Reserva</span>
                    </button>
                    <a href="destinos.php" 
                       class="w-full sm:w-auto px-6 py-4 text-teal-700 hover:text-teal-900 font-semibold text-center transition hover:underline">
                        Cancelar
                    </a>
                </div>

                <!-- Security note -->
                <p class="text-center text-sm text-gray-500 flex items-center justify-center gap-2 mt-4">
                    <i class="fa-solid fa-shield-halved text-teal-600"></i>
                    Tus datos están protegidos y seguros
                </p>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 animate-fade-in">
            <div class="bg-white/80 backdrop-blur p-5 rounded-2xl text-center shadow-lg hover:shadow-xl transition">
                <i class="fa-solid fa-clock text-3xl text-teal-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-1">Respuesta rápida</h3>
                <p class="text-sm text-gray-600">Contacto en 24 horas</p>
            </div>
            <div class="bg-white/80 backdrop-blur p-5 rounded-2xl text-center shadow-lg hover:shadow-xl transition">
                <i class="fa-solid fa-headset text-3xl text-teal-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-1">Soporte 24/7</h3>
                <p class="text-sm text-gray-600">Estamos para ayudarte</p>
            </div>
            <div class="bg-white/80 backdrop-blur p-5 rounded-2xl text-center shadow-lg hover:shadow-xl transition">
                <i class="fa-solid fa-shield-halved text-3xl text-teal-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-1">Pago seguro</h3>
                <p class="text-sm text-gray-600">Transacciones protegidas</p>
            </div>
        </div>
    </div>
</body>
</html>