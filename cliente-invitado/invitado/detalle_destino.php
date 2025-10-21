<?php
session_start();
include('../../php/conexion.php');

// Verificar si hay un usuario logueado con rol 3
$es_invitado_logueado = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 3;

// Obtener nombre del usuario si existe, si no mostrar "Visitante"
$nombre_invitado = isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : "Visitante";

// Obtener el ID de la provincia/destino
$id_provincia = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_provincia <= 0) {
    header('Location: destinos.php');
    exit;
}

// Consultar información del destino
$sql = "SELECT * FROM provincia WHERE id_provincia = ?";
$stmt = $conexion->prepare($sql);
$destino = null;

if ($stmt) {
    $stmt->bind_param('i', $id_provincia);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $destino = $res->fetch_assoc();
    }
    $stmt->close();
}

if (!$destino) {
    header('Location: destinos.php');
    exit;
}

// Procesar imágenes
$imagenes = [];
if (!empty($destino['imagen'])) {
    $imgs = explode(',', $destino['imagen']);
    foreach ($imgs as $img) {
        $img = trim($img);
        if (!empty($img)) {
            $imagenes[] = '../../uploads/' . $img;
        }
    }
}

// Si no hay imágenes, usar placeholder
if (empty($imagenes)) {
    $imagenes[] = 'https://via.placeholder.com/800x600?text=Sin+imagen';
}

// Obtener datos adicionales
$nombre = htmlspecialchars($destino['nombre']);

// Descripción
$descripcion = '';
foreach (['descripcion','detalle','info','descripcion_larga','nota'] as $f) {
    if (isset($destino[$f]) && trim($destino[$f]) !== '') {
        $descripcion = htmlspecialchars($destino[$f]);
        break;
    }
}
if ($descripcion === '') $descripcion = 'Descubre este maravilloso destino lleno de aventuras y experiencias inolvidables.';

// Precio
$precio = 'Consultar';
foreach (['precio', 'precio_desde', 'costo', 'tarifa', 'precio_base'] as $f) {
    if (isset($destino[$f]) && !empty($destino[$f]) && is_numeric($destino[$f])) {
        $precio = '$' . number_format($destino[$f], 2);
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $nombre ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Imagen principal */
        .main-image-container {
            width: 100%;
            height: 500px;
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        /* Miniaturas */
        .thumbnail {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .thumbnail:hover {
            transform: scale(1.05);
            border-color: #0ea5e9;
        }

        .thumbnail.active {
            border-color: #0ea5e9;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        /* Botón reservar */
        .btn-reservar {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            padding: 1rem 3rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-reservar:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(6, 182, 212, 0.6);
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
                    <a href="invitado.php" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
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
                <a href="../../registro-login.html" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                    <span class="nav-text hidden">Iniciar Sesión</span>
                </a>
                <span class="tooltip">Iniciar Sesión</span>
                </div>  
            </nav>
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
        
        <!-- Breadcrumb -->
        <div class="mb-6 fade-in-up">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="destinos.php" class="hover:text-sky-600 transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Volver a destinos
                </a>
            </div>
        </div>

        <!-- Encabezado -->
        <header class="mb-8 fade-in-up">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight"><?= $nombre ?></h1>
                    <p class="text-slate-600 mt-2">Explora este increíble destino</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-r from-sky-600 to-cyan-600 text-white px-6 py-3 rounded-xl shadow-lg">
                        <div class="text-sm font-medium">Precio desde</div>
                        <div class="text-2xl font-bold"><?= $precio ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenedor principal -->
        <div class="grid lg:grid-cols-3 gap-8 fade-in-up">
            
            <!-- Columna izquierda: Galería -->
            <div class="lg:col-span-2">
                <!-- Imagen principal -->
                <div class="main-image-container mb-4">
                    <img id="mainImage" 
                         src="<?= $imagenes[0] ?>" 
                         alt="<?= $nombre ?>"
                         class="main-image"
                         onerror="this.src='https://via.placeholder.com/800x600?text=Sin+imagen'">
                </div>

                <!-- Miniaturas -->
                <?php if (count($imagenes) > 1): ?>
                <div class="grid grid-cols-4 gap-3">
                    <?php foreach ($imagenes as $idx => $img): ?>
                    <div class="thumbnail-container">
                        <img src="<?= $img ?>" 
                             alt="Miniatura <?= $idx + 1 ?>"
                             class="thumbnail <?= $idx === 0 ? 'active' : '' ?>"
                             onclick="changeImage('<?= $img ?>', this)"
                             onerror="this.src='https://via.placeholder.com/200x150?text=Sin+imagen'">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Descripción completa -->
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <h2 class="text-2xl font-bold text-teal-800 mb-4 flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-sky-600"></i>
                        Descripción
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        <?= nl2br($descripcion) ?>
                    </p>
                </div>
            </div>

            <!-- Columna derecha: Info y reservar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 sticky top-6">
                    <h3 class="text-xl font-bold text-teal-800 mb-4">Información del destino</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fa-solid fa-map-location-dot text-sky-600 text-xl"></i>
                            <div>
                                <div class="text-sm text-gray-500">Ubicación</div>
                                <div class="font-semibold"><?= $nombre ?></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fa-solid fa-images text-sky-600 text-xl"></i>
                            <div>
                                <div class="text-sm text-gray-500">Galería</div>
                                <div class="font-semibold"><?= count($imagenes) ?> imagen(es)</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fa-solid fa-tag text-sky-600 text-xl"></i>
                            <div>
                                <div class="text-sm text-gray-500">Precio</div>
                                <div class="font-semibold text-xl text-sky-700"><?= $precio ?></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-slate-200">

                    <!-- Características adicionales -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Incluye:</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-green-600"></i>
                                Transporte incluido
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-green-600"></i>
                                Guía turístico
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-green-600"></i>
                                Actividades recreativas
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-green-600"></i>
                                Seguro de viaje
                            </li>
                        </ul>
                    </div>

                    <!-- Botón grande de reservar -->
                    <a href="../../registro-login.html" 
                       class="btn-reservar w-full text-center text-white justify-center">
                        <i class="fa-solid fa-calendar-check text-2xl"></i>
                        <span>Reservar Ahora</span>
                    </a>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        <i class="fa-solid fa-shield-halved mr-1"></i>
                        Reserva segura y garantizada
                    </p>
                </div>
            </div>
        </div>

    </main>

    <script type="module" src="../../js/menu.js"></script>
    <script src="../../js/imagenes.js"></script>
</body>
</html>