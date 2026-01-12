<section class="catalog-section">
    <div class="container">
        <!-- Header Section -->
        <div class="catalog-header text-center">
            <h1 class="catalog-title">Descubre los fideos anime</h1>
            <p class="catalog-subtitle">
                Explora todo lo mejor del anime de Crunchyroll Eats y encuentra tus próximos fideos.
            </p>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb-custom">
                <li><a href="index.php">Tienda</a></li>
                <li>/</li>
                <li class="active">Carta</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Sidebar Filters -->
            <div class="col-lg-3" id="filtersSidebar">
                <div class="filters-container">
                    <div class="filters-header">
                        <h4 class="filters-title">Filtrar por:</h4>
                    </div>

                    <form method="GET" action="index.php" id="filter-form">
                        <input type="hidden" name="controller" value="carta">
                        <input type="hidden" name="action" value="index">
                        
                        <div class="filter-group">
                            <button type="button" class="category-toggle" onclick="toggleCategory(this)">
                                <span>Anime</span>
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="category-content">
                                <?php foreach ($series as $serie): ?>
                                    <div class="filter-item">
                                        <input
                                            class="filter-checkbox"
                                            type="checkbox"
                                            name="serie[]"
                                            value="<?= $serie['id_serie'] ?>"
                                            id="serie-<?= $serie['id_serie'] ?>"
                                            onchange="this.form.submit()"
                                            <?= (isset($_GET['serie']) && in_array($serie['id_serie'], $_GET['serie'])) ? 'checked' : '' ?>
                                        >
                                        <label class="filter-label" for="serie-<?= $serie['id_serie'] ?>">
                                            <?= htmlspecialchars($serie['nombre']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9" id="productsCol">
                <!-- Products Header -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <span class="products-count">
                            <strong><?= count($productos) ?></strong> productos
                        </span>
                    </div>

                    <div class="toolbar-right">
                        <button class="btn-toggle-filters" id="toggleFiltersBtn" onclick="toggleFilters()">
                            <i class="fas fa-sliders-h"></i>
                            <span id="filterBtnText">Ocultar Filtros</span>
                        </button>

                        <select 
                            name="sort" 
                            class="sort-select" 
                            onchange="this.form.submit()"
                            form="filter-form"
                        >
                            <option value="">Recomendado</option>
                            <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                            <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
                            <option value="best_sellers" <?= (isset($_GET['sort']) && $_GET['sort'] == 'best_sellers') ? 'selected' : '' ?>>Más Vendidos</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid">
                    <?php foreach ($productos as $producto): 
                        $precioOriginal = (float)$producto['precio'];
                        $precioFinal = (float)$producto['precio_final'];
                        $tieneDescuento = $precioFinal < $precioOriginal;

                        $productoParaCarrito = $producto;
                        $jsonProducto = htmlspecialchars(json_encode($productoParaCarrito), ENT_QUOTES, 'UTF-8');
                    ?>
                        <div class="product-card">
                            <a href="index.php?controller=producto&action=ver&id=<?= $producto['id_producto'] ?>" class="product-link">
                                <div class="product-image-wrapper">
                                    <img
                                        src="<?= $producto['imagen'] ?>"
                                        alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                        class="product-image"
                                    >
                                </div>
                            </a>

                            <div class="product-content">
                                <h3 class="product-name">
                                    <a href="index.php?controller=producto&action=ver&id=<?= $producto['id_producto'] ?>">
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                    </a>
                                </h3>

                                <div class="product-footer mt-auto">
                                    <div class="product-price mb-2">
                                        <?php if ($tieneDescuento): ?>
                                            <span class="currency-price text-muted text-decoration-line-through me-2" style="font-size: 0.9em;"><?= number_format($precioOriginal, 2) ?> €</span>
                                            <span class="currency-price text-danger fw-bold fs-5"><?= number_format($precioFinal, 2) ?> €</span>
                                        <?php else: ?>
                                            <span class="currency-price fw-bold fs-5"><?= number_format($precioOriginal, 2) ?> €</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="product-rating mb-3">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                        <span class="text-muted small ms-1">(4.5)</span>
                                    </div>

                                    <?php 
                                    $onclickAction = isset($_SESSION['usuario']) 
                                        ? "addToCart($jsonProducto)" 
                                        : "window.location.href='index.php?controller=login&action=index'"; 
                                    ?>
                                    <button 
                                        class="btn-add-to-cart" 
                                        onclick="event.preventDefault(); <?= $onclickAction ?>"
                                    >
                                        Añadir al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($productos)): ?>
                        <div class="empty-state">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h3>No se encontraron productos</h3>
                            <p class="text-muted">Intenta ajustar tus filtros de búsqueda.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</section>