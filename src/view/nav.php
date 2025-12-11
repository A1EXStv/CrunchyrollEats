<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crunchyroll Eats - Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body id="body-nav">
    <div class="banner-superior">
        <div class="container">
            <div class="banner-superior-contenido">
                <span>¡Consigue lo último en comida de anime! Compra ahora.</span>
                <a href="#">anime en stream en Crunchyroll</a>
            </div>
        </div>
    </div>

    <nav class="navbar-custom">
        <div class="container">
            <div class="navbar-custom2">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img class="navbar-logo" src="/CrunchyEats_AlexRomeroLozano/public/img/logo.png" alt="Logo CrunchyEats">
                </a>

                <!-- Search Bar -->
                <div class="search-wrapper">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control search-input" 
                            placeholder="Buscar platos..."
                            aria-label="Buscar platos"
                        >
                        <button class="search-btn" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Icons -->
                <div class="nav-icons ms-auto">
                    <a href="<?php echo isset($_SESSION['usuario']) ? 'index.php?controller=Usuario&action=perfil' : 'index.php?controller=login&action=Auth'; ?>"  class="nav-icon" title="Mi cuenta">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="<?php echo isset($_SESSION['usuario']) ? 'index.php?controller=Carrito&action=ver' : 'index.php?controller=login&action=Auth'; ?>"  class="nav-icon" title="Carrito">
                        <i class="fas fa-shopping-cart"></i>
                    </a>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=login&action=logout">Cerrar sesión</a>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="container">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="#">Series</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Carta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacta</a>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Menú desplegable mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="#">Series</a></li>
            <li><a href="#">Carta</a></li>
            <li><a href="#">Contacta</a></li>
            <li><a href="<?php echo isset($_SESSION['usuario']) ? 'index.php?controller=Usuario&action=perfil' : 'index.php?controller=Auth&action=login'; ?>">Mi Cuenta</a></li>
        </ul>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav"></nav>

<script>
        // Toggle menú mobile
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>