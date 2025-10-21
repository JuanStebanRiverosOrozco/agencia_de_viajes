<?php
include("../php/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = trim($_POST["precio"]);
    
    if (!empty($nombre)) {
        // Procesar múltiples imágenes
        $imagenesSubidas = [];
        
        if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
            $totalArchivos = count($_FILES['imagenes']['name']);
            $uploadDir = '../uploads/';
            
            // Crear carpeta si no existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            for ($i = 0; $i < $totalArchivos; $i++) {
                $nombreArchivo = $_FILES['imagenes']['name'][$i];
                $tmpName = $_FILES['imagenes']['tmp_name'][$i];
                $error = $_FILES['imagenes']['error'][$i];
                $size = $_FILES['imagenes']['size'][$i];
                
                // Validar archivo
                if ($error === 0 && $size > 0) {
                    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
                    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp','avif'];
                    
                    if (in_array($extension, $extensionesPermitidas)) {
                        // Generar nombre único
                        $nuevoNombre = uniqid('img_', true) . '.' . $extension;
                        $rutaDestino = $uploadDir . $nuevoNombre;
                        
                        if (move_uploaded_file($tmpName, $rutaDestino)) {
                            $imagenesSubidas[] = $nuevoNombre;
                        }
                    }
                }
            }
        }
        
        // Convertir array de imágenes a string separado por comas
        $imagenesStr = !empty($imagenesSubidas) ? implode(',', $imagenesSubidas) : '';
        
        // Insertar en base de datos
        $stmt = $conexion->prepare("INSERT INTO provincia (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagenesStr);
        $stmt->execute();
        
        header("Location: provincias.php");
        exit;
    } else {
        $error = "El nombre no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Provincia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .preview-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }
    .preview-item {
      position: relative;
      aspect-ratio: 1;
      border-radius: 0.5rem;
      overflow: hidden;
      border: 2px solid #e5e7eb;
    }
    .preview-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .remove-btn {
      position: absolute;
      top: 4px;
      right: 4px;
      background: rgba(239, 68, 68, 0.9);
      color: white;
      border: none;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      transition: background 0.2s;
    }
    .remove-btn:hover {
      background: rgba(220, 38, 38, 1);
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
  
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Agregar Provincia</h1>
    
    <?php if (!empty($error)) echo "<p class='text-red-500 mb-3'>$error</p>"; ?>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <!-- Nombre -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
        <input type="text" name="nombre" placeholder="Nombre de la provincia"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-sky-300" required>
      </div>
      
      <!-- Descripción -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
        <textarea name="descripcion" rows="3" placeholder="Descripción del destino"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-sky-300"></textarea>
      </div>
      
      <!-- Precio -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
        <input type="number" name="precio" step="0.01" placeholder="0.00"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-sky-300">
      </div>
      
      <!-- Imágenes -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes</label>
        <input type="file" id="fileInput" name="imagenes[]" multiple accept="image/*" 
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-sky-300"
          onchange="previewImages(event)">
        
        <!-- Vista previa -->
        <div id="preview" class="preview-container"></div>
      </div>
      
      <!-- Botones -->
      <div class="flex justify-between pt-4">
        <a href="provincias.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md">Cancelar</a>
        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">Guardar</button>
      </div>
    </form>
  </div>

  <script>
    let selectedFiles = [];

    function previewImages(event) {
      const files = Array.from(event.target.files);
      
      // Agregar nuevos archivos
      selectedFiles = selectedFiles.concat(files);
      
      updatePreview();
      updateFileInput();
    }

    function removeImage(index) {
      selectedFiles.splice(index, 1);
      updatePreview();
      updateFileInput();
    }

    function updatePreview() {
      const preview = document.getElementById('preview');
      preview.innerHTML = '';
      
      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          const div = document.createElement('div');
          div.className = 'preview-item';
          div.innerHTML = `
            <img src="${e.target.result}" alt="Preview ${index + 1}">
            <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
          `;
          preview.appendChild(div);
        };
        
        reader.readAsDataURL(file);
      });
    }

    function updateFileInput() {
      const dataTransfer = new DataTransfer();
      selectedFiles.forEach(file => dataTransfer.items.add(file));
      document.getElementById('fileInput').files = dataTransfer.files;
    }
  </script>

</body>
</html>