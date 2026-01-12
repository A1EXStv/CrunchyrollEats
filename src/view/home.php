

    <section class="hero-section">
        <img src="public/img/primera_imagen_home.png" alt="Imagen publicidad">
    </section>

    <section class="py-5">
        <div class="container">
            <div class="cabecera-products-section mb4">
                <h2>Descuentos Navideños</h2>
                <a href="index.php?controller=carta&action=index" class="vertodo">Ver todo →</a>
            </div>
            
            <div class="carousel-outer-wrapper">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $chunks = array_chunk($productos, 4);
                        foreach ($chunks as $index => $slide) {
                            $active = $index === 0 ? 'active' : '';
                            echo "<div class='carousel-item $active'><div class='row'>";
                            foreach ($slide as $producto) {
                                $tieneDescuento = (float)$producto['precio_final'] < (float)$producto['precio'];
                                $precioOriginal = (float)$producto['precio'];
                                $precioFinal = (float)$producto['precio_final'];
                                
                                $productoParaCarrito = $producto;
                                $productoParaCarrito['precio_final'] = $precioFinal;
                                $jsonProducto = htmlspecialchars(json_encode($productoParaCarrito), ENT_QUOTES, 'UTF-8');
                                ?>
                                <div class='col-md-3 col-sm-6'>
                                    <div class='product-card'>
                                        <a href="index.php?controller=producto&action=ver&id=<?= $producto['id_producto'] ?>">
                                            <img src='<?= $producto['imagen'] ?>' alt='<?= $producto['nombre'] ?>' class='product-image text-center'>
                                        </a>
                                        <div class='product-info'>
                                            <a href="index.php?controller=producto&action=ver&id=<?= $producto['id_producto'] ?>" class="text-decoration-none text-dark">
                                                <h5><?= $producto['nombre'] ?></h5>
                                            </a>
                                            <div class='product-price'>
                                                <?php if ($tieneDescuento): ?>
                                                    <span class="currency-price text-muted text-decoration-line-through" style="font-size: 0.8em;"><?= number_format($precioOriginal, 2) ?> €</span>
                                                    <span class="currency-price text-danger fw-bold"><?= number_format($precioFinal, 2) ?> €</span>
                                                <?php else: ?>
                                                    <span class="currency-price"><?= number_format($precioOriginal, 2) ?> €</span>
                                                <?php endif; ?>
                                            </div>

                                            <?php 
                                            $onclickAction = isset($_SESSION['usuario']) 
                                                ? "addToCart($jsonProducto)" 
                                                : "window.location.href='index.php?controller=login&action=index'"; 
                                            ?>
                                            <button class="btn-add-cart" onclick="<?= $onclickAction ?>">
                                                Añadir al carrito
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            echo "</div></div>";
                        }
                        ?>
                    </div>
                </div>
                <!-- Botones fuera del div del carrusel pero dentro del wrapper -->
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>
    </section>


    <section class="container my-5">
        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="anime-card">
                    <a href='index.php?controller=carta&action=index&serie[]=20' class='text-decoration-none'>
                    <div class="anime-img">
                        <img src="public/img/pokemon_carta.png" alt="Pokemon"></a>
                    </div>
                    <div class="anime-body">
                        <h4 class="anime-title">Compra Pokémon</h4>
                        <p class="anime-desc">Ash, Pikachu y sus amigos están listos</p>
                    </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="anime-card">
                    <a href='index.php?controller=carta&action=index&serie[]=10' class='text-decoration-none'>
                    <div class="anime-img">
                    <img src="public/img/evangelion_carta.png" alt="Evangelion">
                    </div>
                    <div class="anime-body">
                        <h4 class="anime-title">Compra Evangelion</h4>
                        <p class="anime-desc">Asuka y Rei te esperan para cumplir la misión</p>
                    </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="anime-card">
                    <a href='index.php?controller=carta&action=index&serie[]=8' class='text-decoration-none'>
                    <div class="anime-img">
                        <img src="public/img/digimon_carta.png" alt="Digimon">
                    </div>
                    <div class="anime-body">
                        <h4 class="anime-title">Compra Digimon</h4>
                        <p class="anime-desc">Agumon y Tai te espera para pelear y salvar a los humanos</p>
                    </div>
                    </a>
                </div>
            </div>

        </div>
    </section>

<section class="two-card-section">
    <div class="container">
        <div class="two-card-row">
            <div class="two-card-col">
                <div class="two-card">
                    <a href='index.php?controller=carta&action=index&serie[]=21' class='text-decoration-none'>
                    <div class="two-card-image">
                        <img src="public/img/yuyu_carta_dos.png" alt="Imagen 1">
                    </div>
                    <div class="two-card-content">
                        <h4 class="two-card-title">Novedades</h4>
                        <p class="two-card-desc">Descubre los nuevos productos.</p>
                    </div>
                    </a>
                </div>
            </div>
            <div class="two-card-col">
                <div class="two-card">
                    <a href='index.php?controller=carta&action=index&serie[]=3' class='text-decoration-none'>
                    <div class="two-card-image">
                        <img src="public/img/demon_carta_dos.png" alt="Imagen 2">
                    </div>
                    <div class="two-card-content">
                        <h4 class="two-card-title">Novedades</h4>
                        <p class="two-card-desc">Descubre los nuevos productos.</p>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

    

   <section class="brands-section">
    <div class="container">
        <div>
            <h3>Comprar por Series</h3>
        </div>
        <div class="row g-5">
            <?php
            foreach ($series as $serie) {
                echo "
                <div class='col-md-3 col-sm-6'>
                    <a href='index.php?controller=carta&action=index&serie[]={$serie['id_serie']}' class='text-decoration-none'>
                        <div class='brand-logo'>
                            <img src='{$serie['imagen']}' alt='{$serie['nombre']}' class='product-image'>
                        </div>
                    </a>
                </div>
                ";
            }
            ?>
        </div>
    </div>
</section>


    <section class="features-section">
        <div class="container">
            <h2 class="text-center mb-5">¡Tu destino definitivo para comprar fideos anime!</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-gift" style="color: #ff640b;"></i>
                        </div>
                        <h5>Ofertas Constantes</h5>
                        <p>Ofertas activas cada día.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-lock" style="color: #fab818;"></i>
                        </div>
                        <h5>Compra Segura</h5>
                        <p>Tu privacidad es nuestra mayor prioridad. Si necesitas asistencia, contacta con nuestro personal de soporte.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-regular fa-thumbs-up" style="color: #2abdbb;"></i>
                        </div>
                        <h5>Licencias Oficiales</h5>
                        <p>Tenemos contacto directo con tus licencias y franquicias favoritas. Todos nuestros productos cuentan con su licencia oficial.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
