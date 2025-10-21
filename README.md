# ✈️ Proyecto: Agencia de Viajes

## 📘 Descripción General

**Agencia de Viajes** es un sistema web desarrollado con **HTML, CSS (TailwindCSS), JavaScript y PHP** conectado a una base de datos **MySQL**.  
El objetivo principal es permitir la **gestión de destinos, reservas y usuarios** de manera moderna, organizada y con una interfaz atractiva basada en principios de **UX/UI**.

El proyecto está diseñado para tres tipos de usuarios:
1. **Administrador** → Gestiona usuarios, provincias y mantiene el control del sistema.
2. **Cliente Registrado** → Puede ver destinos, reservar y administrar su perfil.
3. **Invitado** → Puede explorar los destinos disponibles pero sin realizar reservas.

---

## 🧩 Estructura del Proyecto
agencia_de_viajes/
│
├── admi/                      → Panel de administración
│   ├── admi.php
│   ├── usuarios.php
│   ├── agregar_usuario.php
│   ├── editar_usuario.php
│   └── eliminar_usuario.php
│
├── cliente-invitado/          → Sección para clientes e invitados
│   ├── cliente/
│   │   ├── cliente.php
│   │   ├── destinos.php
│   │   ├── reservar.php
│   │   └── perfil.php
│   └── invitado/
│       ├── invitado.php
│       └── destinos.php
│
├── img/                       → Imágenes del sitio (cartagena.avif, china.avif, etc.)
│
├── js/                        → Archivos JavaScript (interactividad)
│   ├── imagenes.js
│   ├── menu.js
│   └── registro-login.js
│
├── php/                       → Backend y conexión a la base de datos
│   ├── conexion.php
│   ├── login.php
│   └── registrar.php
│
├── uploads/                   → Imágenes subidas por los usuarios
│
├── usuario/                   → Panel de usuario registrado
│   ├── usuario.php
│   ├── reservas.php
│   ├── perfil.php
│   └── provincias.php
│
├── index.html                 → Página principal
├── registro-login.html         → Formulario de registro e inicio de sesión
└── .git/                      → Repositorio de control de versiones


## 🧠 Funcionalidades Principales

- **CRUD completo** (Crear, Leer, Actualizar y Eliminar) para usuarios, reservas y provincias.
- **Inicio de sesión y registro** con validación en PHP y MySQL.
- **Diseño responsivo** con **TailwindCSS**.
- **Animaciones JavaScript** en el menú y las imágenes.
- **Mensajes de confirmación**: registro guardado, eliminado o actualizado.
- **Subida de imágenes** para destinos o perfiles.
- **Separación clara entre frontend, backend y base de datos.**

---

## 🎨 UX / UI y Diseño

- **Framework de estilos:** TailwindCSS  
- **Paleta de colores sugerida:**
  - Azul (#2563EB)
  - Blanco (#FFFFFF)
  - Gris suave (#F3F4F6)
  - Amarillo (#FACC15)
- **Tipografía:** Inter / Sans-serif  
- **Favicon y logosímbolo**: creados con herramientas de diseño libre (ej. Canva, Figma).  
- **Imágenes:** de libre uso obtenidas de *Unsplash* o *Pexels*.  
- **Principios aplicados:** claridad, consistencia, accesibilidad y retroalimentación visual.

---

## 🧾 Base de Datos

**Nombre:** `gestor_viajes`

### Tablas principales:
- **usuarios** → Información de registro (nombre, correo, contraseña, rol).
- **compania** → Datos de las compañías o agencias afiliadas.
- **provincia** → Destinos turísticos disponibles.
- **reservas** → Registra las reservas realizadas por los clientes.

Cada tabla está relacionada mediante **llaves foráneas** para mantener la coherencia entre usuarios, reservas y destinos.

---

## ⚙️ Tecnologías Utilizadas

| Componente | Tecnología |
|-------------|-------------|
| Frontend | HTML5, TailwindCSS, JavaScript |
| Backend | PHP 8.2 |
| Base de Datos | MySQL |
| Control de Versiones | Git y GitHub |
| Servidor Local | XAMPP / Apache |
| Sistema Operativo | EndeavourOS (Linux) |

---


## 🧰 Requisitos Funcionales (RF)

- RF01: El sistema debe permitir el registro y autenticación de usuarios.
- RF02: El usuario debe poder ver los destinos disponibles.
- RF03: El cliente debe poder realizar reservas.
- RF04: El administrador puede gestionar usuarios, provincias y reservas.
- RF05: El sistema debe mostrar mensajes de confirmación (registro exitoso, eliminado, etc.).

---

## ✅ Lista de Chequeo

| Elemento | Cumplido |
|-----------|-----------|
| Diagrama Entidad-Relación | ✅ |
| Mockups y mapa de navegación | ✅ |
| Diagrama de casos de uso | ✅ |
| Indexado y documentación del código | ✅ |
| Interfaz con TailwindCSS | ✅ |
| Animaciones JavaScript | ✅ |
| Coherencia Backend-Frontend-DB | ✅ |
| Principios UX/UI aplicados | ✅ |
| README.md implementado | ✅ |
| Subida al repositorio GitHub | ✅ |

---

## 🧑‍💻 Instalación y Uso

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/usuario/agencia_de_viajes.git
