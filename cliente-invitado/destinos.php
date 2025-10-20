<?php
session_start();
include('../php/conexion.php');

// Verificar si hay un usuario logueado con rol 3
$es_invitado_logueado = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 3;

// Obtener nombre del usuario si existe, si no mostrar "Visitante"
$nombre_invitado = isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : "Visitante";

// Selecciona todas las columnas de provincia y ordénalas por el campo "nombre" si existe,
// si no, usa la segunda columna disponible para ordenar.
$orderBy = 'nombre';
$colsRes = $conexion->query("SHOW COLUMNS FROM provincia");
$columns = [];
if ($colsRes) {
    while ($c = $colsRes->fetch_assoc()) {
        $columns[] = $c['Field'];
    }
    // si no existe 'nombre' intenta usar la segunda columna como orden
    if (!in_array('nombre', $columns) && isset($columns[1])) {
        $orderBy = $columns[1];
    } elseif (!in_array('nombre', $columns)) {
        $orderBy = $columns[0];
    }
}

// Seleccionar TODAS las columnas para obtener las imágenes
$sql = "SELECT * FROM provincia ORDER BY `$orderBy`";
$stmt = $conexion->prepare($sql);
$provincias = [];
if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $provincias[] = [
                'id' => $row['id_provincia'],
                'nombre' => htmlspecialchars($row['nombre']),
                'raw' => $row
            ];
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Destinos | Sol & Mar</title>
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

        /* Tarjeta con imagen de fondo */
        .destination-card {
            position: relative;
            height: 420px;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .destination-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .destination-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, 
                rgba(0,0,0,0.1) 0%, 
                rgba(0,0,0,0.4) 50%, 
                rgba(0,0,0,0.9) 100%
            );
            z-index: 1;
            transition: opacity 0.4s ease;
        }

        .destination-card:hover::before {
            opacity: 0.95;
        }

        .destination-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .destination-card:hover .destination-bg {
            transform: scale(1.1);
        }

        .destination-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem;
            color: white;
        }

        .price-badge {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.125rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
            animation: pulse-subtle 2s infinite;
        }

        @keyframes pulse-subtle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .destination-title {
            font-size: 1.75rem;
            font-weight: 800;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            margin-bottom: 0.75rem;
        }

        .destination-desc {
            font-size: 0.875rem;
            line-height: 1.5;
            color: rgba(255,255,255,0.9);
            text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.5);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
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
                    <a href="cliente-invitado.php" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
                        <i class="fa-solid fa-house text-xl"></i>
                        <span class="nav-text hidden">Inicio</span>
                    </a>
                    <span class="tooltip">Inicio</span>
                </div>

                <div class="relative group">
                    <a href="destinos.php" class="flex items-center gap-3 px-6 py-3 bg-cyan-800 rounded-lg transition">
                        <i class="fa-solid fa-plane-departure text-xl"></i>
                        <span class="nav-text hidden">Ver Paquetes</span>
                    </a>
                    <span class="tooltip">Ver Paquetes</span>
                </div>

                <?php if (!$es_invitado_logueado): ?>
                <div class="relative group">
                    <a href="../registro-login.html" class="flex items-center gap-3 px-6 py-3 hover:bg-cyan-800 rounded-lg transition">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                        <span class="nav-text hidden">Iniciar Sesión</span>
                    </a>
                    <span class="tooltip">Iniciar Sesión</span>
                </div>
                <?php endif; ?>
            </nav>
        </div>

        <div class="border-t border-cyan-700 py-4 hover:bg-cyan-800">
            <div class="relative group">
                <a href="../index.html" class="flex items-center gap-3 px-6 py-3 transition rounded-lg">
                    <i class="fa-solid fa-door-open"></i>
                    <span class="nav-text hidden">Cerrar sesión</span>
                </a>
                <span class="tooltip">Cerrar sesión</span>
            </div>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main id="mainContent" class="ml-20 p-6 pl-8 sm:pl-10 lg:pl-16 transition-all duration-300 relative z-10 bg-gradient-to-br from-white via-slate-50 to-sky-50 min-h-screen">
        <header class="mb-8 mt-4">
            <h1 class="text-4xl font-extrabold text-sky-800 tracking-tight">Destinos & Provincias</h1>
            <p class="text-slate-600 mt-1">Explora los mejores destinos disponibles para tu próxima aventura</p>
        </header>

        <!-- Tarjeta descriptiva encima del grid -->
        <div class="mb-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col sm:flex-row items-start sm:items-center gap-4 border border-slate-200">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-compass-drafting text-4xl text-sky-600"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-teal-800">Explora nuestras provincias</h2>
                    <p class="text-gray-600 mt-2">Descubre los mejores destinos, actividades y paquetes disponibles. Filtra por provincia y reserva tu viaje con facilidad.</p>
                </div>
                <div class="text-right">
                    <span class="text-sky-700 font-bold text-2xl"><?= count($provincias) ?></span>
                    <div class="text-sm text-gray-500">destinos disponibles</div>
                </div>
            </div>
        </div>

        <?php if (empty($provincias)): ?>
            <div class="bg-white p-8 rounded-2xl shadow-lg text-gray-600 border border-slate-200">
                <i class="fa-solid fa-circle-exclamation text-3xl text-gray-400 mb-3"></i>
                <p>No hay destinos o provincias disponibles por ahora.</p>
            </div>
        <?php else: ?>
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($provincias as $p): 
                    // Descripción
                    $desc = '';
                    foreach (['descripcion','detalle','info','descripcion_larga','nota'] as $f) {
                        if (isset($p['raw'][$f]) && trim($p['raw'][$f]) !== '') {
                            $desc = htmlspecialchars($p['raw'][$f]);
                            break;
                        }
                    }
                    if ($desc === '') $desc = 'Descubre este maravilloso destino lleno de aventuras y experiencias inolvidables.';
                    
                    // OBTENER SOLO LA PRIMERA IMAGEN
                    $imgSrc = 'https://via.placeholder.com/600x400?text=Sin+imagen';
                    
                    if (!empty($p['raw']['imagen'])) {
                        $imagenes = explode(',', $p['raw']['imagen']);
                        $primeraImagen = trim($imagenes[0]);
                        
                        if (!empty($primeraImagen)) {
                            $imgSrc = '../uploads/' . $primeraImagen;
                        }
                    }
                    
                    // OBTENER EL PRECIO
                    $precio = 'Consultar';
                    foreach (['precio', 'precio_desde', 'costo', 'tarifa', 'precio_base'] as $f) {
                        if (isset($p['raw'][$f]) && !empty($p['raw'][$f]) && is_numeric($p['raw'][$f])) {
                            $precio = '$' . number_format($p['raw'][$f], 2);
                            break;
                        }
                    }
                ?>
                
                <!-- Tarjeta con imagen de fondo -->
                <article class="destination-card">
                    <!-- Imagen de fondo -->
                    <img src="<?= $imgSrc ?>" 
                         alt="<?= $p['nombre'] ?>" 
                         class="destination-bg"
                         onerror="this.src='https://via.placeholder.com/600x400?text=Sin+imagen'">
                    
                    <!-- Contenido superpuesto -->
                    <div class="destination-content">
                        <!-- Parte superior: Precio -->
                        <div class="flex justify-end">
                            <div class="price-badge">
                                <i class="fa-solid fa-tag"></i>
                                <span><?= $precio ?></span>
                            </div>
                        </div>
                        
                        <!-- Parte inferior: Información y botones -->
                        <div>
                            <h2 class="destination-title"><?= $p['nombre'] ?></h2>
                            <p class="destination-desc mb-4">
                                <?= strip_tags($desc) ?>
                            </p>
                            
                            <!-- Botones -->
                            <div class="flex gap-3">
                                <a href="./detalle_destino.php?id=<?= urlencode($p['id']) ?>" 
                                   class="btn-primary flex-1 text-center">
                                    <i class="fa-solid fa-circle-info mr-2"></i>Más detalles
                                </a>
                                <a href="./reservar.php?provincia=<?= urlencode($p['id']) ?>" 
                                   class="btn-secondary flex-1 text-center">
                                    <i class="fa-solid fa-calendar-check mr-2"></i>Reservar
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
    <script type="module" src="../js/menu.js"></script>
</body>
</html>