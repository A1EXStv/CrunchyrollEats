# CrunchyrollEats 🍜

**CrunchyrollEats** es una aplicación web de gestión de restaurante temático (inspirado en anime/Crunchyroll) desarrollada en PHP nativo siguiendo el patrón MVC. Permite a los usuarios explorar una carta de productos, realizar pedidos y gestionar su perfil, mientras que los administradores cuentan con un panel de control completo (SPA) para gestionar el negocio.

## 🚀 Funcionalidades

### 👤 Parte Pública (Cliente)
- **Catálogo de Productos**: Visualización de productos categorizados (con filtrado y ordenación).
- **Detalle de Producto**: Información detallada de cada plato, incluyendo ingredientes.
- **Carrito de Compras**: Gestión de productos añadidos al pedido antes del checkout.
- **Proceso de Compra (Checkout)**: Simulación de pago y envío de pedidos.
- **Gestión de Perfil**: Los usuarios pueden registrarse, iniciar sesión y actualizar sus datos.
- **Autenticación**: Sistema seguro de Login y Registro.

### 🛡️ Panel de Administración (SPA)
El panel de administración es una **Single Page Application (SPA)** construida con JavaScript Vanilla, que interactúa con una API backend para una experiencia fluida sin recargas.
- **Dashboard**: Vista general del sistema.
- **Gestión de Productos**: Crear, editar y eliminar productos (asociados a Series y Categorías).
- **Gestión de Series**: Administración de las series de anime temáticas.
- **Gestión de Usuarios**: Visualización de usuarios y roles.
- **Gestión de Pedidos**: Control del estado de los pedidos (Pendiente ➡️ Preparando ➡️ Entregado).
- **Descuentos**: Creación y activación/desactivación de códigos de descuento.
- **Logs de Auditoría**: Registro de acciones importantes realizadas en el sistema.

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP (Nativo, estructura MVC, DAO Pattern).
- **Frontend**: HTML5, CSS3, JavaScript.
- **Base de Datos**: MySQL.
- **Arquitectura**: 
    - **MVC** (Modelo-Vista-Controlador) para la aplicación principal.
    - **SPA** (Single Page Application) para el panel de admin.
    - **DAO** (Data Access Object) para la abstracción de base de datos.

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio**:
   ```bash
   git clone <url-del-repositorio>
   ```

2. **Base de Datos**:
   - Crear una base de datos llamada `crunchyeats` (o el nombre deseado).
   - Importar el esquema de la base de datos desde `sql/createdatabase.txt`.
   - (Opcional) Importar datos de prueba desde `sql/inserts.txt`.

3. **Configuración**:
   - Abrir el archivo `config/config.php`.
   - Asegurarse de que las credenciales de la base de datos sean correctas:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASSWORD', ''); // Tu contraseña
     define('DB_DATABASE', 'crunchyeats');
     ```

4. **Ejecución**:
   - Desplegar el proyecto en un servidor web compatible con PHP (ej. Apache en XAMPP/WAMP/LAMP).
   - Acceder a `http://localhost/CrunchyrollEats/`.

## 📂 Estructura del Proyecto

- `src/controller`: Controladores de la aplicación (lógica de negocio).
- `src/model`: Modelos de datos.
- `src/view`: Plantillas y vistas HTML/PHP.
- `src/DAO`: Objetos de Acceso a Datos (interacción con BBDD).
- `api`: Endpoints para el panel de administración.
- `public`: Archivos estáticos (CSS, JS, Imágenes).
- `sql`: Scripts SQL para la creación de la BBDD.
