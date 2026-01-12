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
                <a class="navbar-brand" href="index.php">
                    <img class="navbar-logo" src="public/img/logo.png" alt="Logo CrunchyEats">
                </a>

                <!-- Icons -->
                <div class="nav-icons ms-auto" style="display: flex; align-items: center; gap: 15px;">
                    <!-- Currency Selector -->
                    <select id="currencySelect" class="form-select form-select-sm" style="width: auto; background: rgba(255,255,255,0.1); color: #EC5119; border: 1px solid rgba(255,255,255,0.2); cursor: pointer;">
                        <option value="EUR" selected>€ EUR</option>
                        <option value="USD">$ USD</option>
                        <option value="JPY">¥ JPY</option>
                    </select>

                    <a href="<?php echo isset($_SESSION['usuario']) ? 'index.php?controller=perfil&action=index' : 'index.php?controller=login&action=index'; ?>"  class="nav-icon" title="Mi cuenta">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="<?= isset($_SESSION['usuario']) ? 'index.php?controller=carrito&action=ver' : 'index.php?controller=login&action=index'; ?>"  class="nav-icon position-relative" title="Carrito">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.6rem;">
                            0
                        </span>
                    </a>


                    <?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
                        <a href="public/admin/index.html" class="nav-icon" title="Panel Admin">
                            <i class="fas fa-cogs"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <div class="nav-item">
                             <a class="nav-link text-white ms-3" href="index.php?controller=login&action=logout">Cerrar sesión</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="container">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=carta&action=index">Carta</a>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Menú desplegable mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="index.php?controller=serie&action=index">Series</a></li>
            <li><a href="index.php?controller=carta&action=index">Carta</a></li>
            <li><a href="#">Contacta</a></li>
            <li><a href="<?php echo isset($_SESSION['usuario']) ? 'index.php?controller=perfil&action=index' : 'index.php?controller=login&action=index'; ?>">Mi Cuenta</a></li>
            <?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
                <li><a href="public/admin/index.html">Panel Admin</a></li>
            <?php endif; ?>
        </ul>
    </div>

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